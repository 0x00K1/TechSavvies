<?php
/********************************************************************
 *  fetchTable.php  –  generic CRUD + structure for the Root Dashboard
 *  ---------------------------------------------------------------
 *  supports  action = fetch | update | delete | create | getStructure
 *            tableName = products | customers | orders | payments | reviews
 ********************************************************************/

// ──────────────────────────────────────────────────────────────────
// 0.  Secure session & prerequisites
// ──────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../../../includes/secure_session.php';
header('Content-Type: application/json');

if (!isset($_SESSION['root_id'])) {                // Root only
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

require_once __DIR__ . '/table_config.php';
require_once __DIR__ . '/../../../includes/db.php';

// ──────────────────────────────────────────────────────────────────
// 1.  Request parameters
// ──────────────────────────────────────────────────────────────────
$action        = strtolower($_GET['action']    ?? 'fetch');
$tableName     = $_GET['tableName']           ?? '';
$rowsPerPage   = max(1, (int)($_GET['rowsPerPage'] ?? 10));
$pageNumber    = max(1, (int)($_GET['page'] ?? 1));
$offset        = ($pageNumber - 1) * $rowsPerPage;
$sortBy        = $_GET['sortBy']              ?? null;
$sortDirection = strtolower($_GET['sortDirection'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
$searchTerm    = trim($_GET['search'] ?? '');
$searchAttr    = trim($_GET['searchAttribute'] ?? '');

// ──────────────────────────────────────────────────────────────────
// 2.  Basic validation
// ──────────────────────────────────────────────────────────────────
if (!isset($tableConfig[$tableName])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid table']));
}
$cfg = $tableConfig[$tableName];   // shorthand

// ──────────────────────────────────────────────────────────────────
// 3.  Dispatcher
// ──────────────────────────────────────────────────────────────────
switch ($action) {

    case 'fetch':
        return fetchTable($pdo, $tableName, $cfg, $rowsPerPage, $offset,
                          $sortBy, $sortDirection, $searchTerm, $searchAttr);

    case 'update':
        return updateRow($pdo, $tableName, $cfg);

    case 'delete':
        return deleteRow($pdo, $tableName, $cfg);

    case 'create':                                   // alias "insert"
    case 'insert':
        return createRow($pdo, $tableName, $cfg);

    case 'getstructure':
        echo json_encode([
            'tableName'        => $tableName,
            'displayColumns'   => $cfg['displayColumns'],
            'editableColumns'  => $cfg['editableColumns'],
            'deletable'        => $cfg['deletable'],
            'primaryKey'       => $cfg['primaryKey'],
            'searchableColumns'=> $cfg['searchableColumns'],
            'csrf_token'       => $_SESSION['csrf_token']
        ]);
        break;
    
    case 'grantroot':
        return grantRoot($pdo, $tableName, $cfg);
    case 'deleteroot':
        return deleteRootWithPassword($pdo, $tableName, $cfg);
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}

/*==================================================================
=  Shared helpers
==================================================================*/

/**
 * Hard-fail if CSRF token is absent or wrong.
 */
function requireCsrf(): void
{
    $token = $_POST['csrf_token'] ?? ($_GET['csrf_token'] ?? null);

    // accept JSON payloads too
    if (!$token && str_starts_with($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $token = $body['csrf_token'] ?? null;
        foreach ($body as $k => $v) $_POST[$k] = $v;   // normalise into $_POST
    }

    if (!$token || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit(json_encode(['error' => 'Invalid CSRF token']));
    }
}

/**
 * Add a CSRF token and paging meta to every fetch response.
 */
function addMeta(array $resp, array $cfg, int $totalRows,
                 int $page, int $perPage): array
{
    $resp['primaryKey']      = $cfg['primaryKey'];
    $resp['primaryKeyName']  = $cfg['primaryKey'];
    $resp['editableColumns'] = $cfg['editableColumns'];
    $resp['totalRecords']    = $totalRows;
    $resp['page']            = $page;
    $resp['totalPages']      = ceil($totalRows / $perPage) ?: 1;
    $resp['csrf_token']      = $_SESSION['csrf_token'];
    return $resp;
}

/*==================================================================
=  ACTION: fetch
==================================================================*/
function fetchTable(PDO $pdo, string $tbl, array $cfg,
                    int $limit, int $offset,
                    ?string $sortBy, string $sortDir,
                    string $search, string $searchAttr)
{
    // 1) optional category filter
    $categoryFilter = $_GET['filterCategory'] ?? '';

    // 2) build WHERE clauses
    $where = [];
    $bind  = [];

    if ($tbl === 'products' && $categoryFilter !== '') {
        $where[] = "p.category_id = :categoryFilter";
        $bind[':categoryFilter'] = $categoryFilter;
    }
    if ($search !== '') {
        if ($searchAttr && in_array($searchAttr, $cfg['searchableColumns'], true)) {
            $colRef = $tbl === 'products' ? "p.{$searchAttr}" : $searchAttr;
            $where[]       = "{$colRef} LIKE :term";
            $bind[':term'] = "%{$search}%";
        } else {
            $subs = [];
            foreach ($cfg['searchableColumns'] as $i => $col) {
                $p      = ":t{$i}";
                // qualify each with p. when in products
                $colRef = $tbl === 'products' ? "p.{$col}" : $col;
                $subs[]     = "{$colRef} LIKE {$p}";
                $bind[$p]   = "%{$search}%";
            }
            $where[] = '(' . implode(' OR ', $subs) . ')';
        }
    }

    $whereClause = $where ? ' WHERE '.implode(' AND ', $where) : '';

    // 3) count
    $from = $tbl==='products'
          ? "products p LEFT JOIN categories c ON p.category_id=c.category_id"
          : $tbl;
    $cntSql = "SELECT COUNT(*) FROM {$from}{$whereClause}";
    $cstmt  = $pdo->prepare($cntSql);
    foreach ($bind as $k=>$v) $cstmt->bindValue($k,$v);
    $cstmt->execute();
    $total = (int)$cstmt->fetchColumn();

    // 4) data
    if ($tbl==='products') {
        // build select list without arrow-functions
        $select = [];
        foreach ($cfg['displayColumns'] as $col) {
            if ($col === 'category') {
                $select[] = 'c.category_name AS category';
            } else {
                $select[] = "p.{$col}";
            }
        }
        $cols = implode(', ', $select);

        $dataSql = "
          SELECT {$cols}, p.category_id
            FROM products p
       LEFT JOIN categories c ON p.category_id=c.category_id
            {$whereClause}
         ORDER BY ".(
             $sortBy && in_array($sortBy,$cfg['displayColumns'],true)
               ? "{$sortBy} {$sortDir}"
               : "p.{$cfg['primaryKey']} ASC"
           )."
           LIMIT :lim OFFSET :off";
    } else {
        $cols = implode(', ',$cfg['displayColumns']);
        $dataSql = "
          SELECT {$cols}
            FROM {$tbl}
            {$whereClause}
         ORDER BY ".(
             $sortBy && in_array($sortBy,$cfg['displayColumns'],true)
               ? "{$sortBy} {$sortDir}"
               : "{$cfg['primaryKey']} ASC"
           )."
           LIMIT :lim OFFSET :off";
    }

    $dstmt = $pdo->prepare($dataSql);
    foreach ($bind as $k=>$v) $dstmt->bindValue($k,$v);
    $dstmt->bindValue(':lim',$limit,PDO::PARAM_INT);
    $dstmt->bindValue(':off',$offset,PDO::PARAM_INT);
    $dstmt->execute();
    $rows = $dstmt->fetchAll(PDO::FETCH_ASSOC);

    // 5) dummy Action column
    if (!empty($cfg['editableColumns'])) {
        foreach ($rows as &$r) $r['Action']='actions';
    }

    echo json_encode(
      addMeta(['records'=>$rows], $cfg, $total, ($offset/$limit)+1, $limit)
    );
}

/*==================================================================
=  ACTION: update
==================================================================*/
function updateRow(PDO $pdo, string $tbl, array $cfg)
{
    requireCsrf();

    $pkField = $cfg['primaryKey'];
    $pkVal   = $_POST[$pkField] ?? ($_GET['id'] ?? null);

    if ($pkVal === null) {
        http_response_code(400);
        exit(json_encode(['error' => "Missing primary key '{$pkField}'"]));
    }

    /* gather editable columns */
    $set = [];
    foreach ($cfg['editableColumns'] as $col) {
        if (isset($_POST[$col])) {
            $set[$col] = $_POST[$col];
        }
    }
    if (!$set) {
        http_response_code(400);
        exit(json_encode(['error' => 'No editable fields supplied']));
    }

    $updates = [];
    $params = [];
    
    foreach ($set as $col => $val) {
        $updates[] = "$col = ?";
        $params[] = $val;
    }
    
    // Add updated_at for tables that have this field
    if ($tbl === 'products') {
        $updates[] = "updated_at = CURRENT_TIMESTAMP";
    }
    
    $updateStr = implode(', ', $updates);
    $sql = "UPDATE {$tbl} SET {$updateStr} WHERE {$pkField} = ?";
    
    // Add primary key value as the last parameter
    $params[] = $pkVal;
    
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute($params);
    } catch (PDOException $e) {
         // SQLSTATE 23000 = integrity constraint violation (e.g. duplicate key)
        if (isset($e->errorInfo[0]) && $e->errorInfo[0] === '23000') {
            // Extract the key name from MySQL’s message, e.g. “… for key 'email'”
            if (preg_match("/for key '([^']+)'/", $e->errorInfo[2], $m)) {
                $field = $m[1];
                $msg   = ucfirst($field) . " already exists.";
            } else {
                $msg = 'Integrity constraint violation.';
            }
            http_response_code(409);
            exit(json_encode(['success'=>false, 'error'=>$msg]));
        }
        // fallback for other PDO errors
        http_response_code(500);
        exit(json_encode(['success'=>false, 'error'=>'Database error: '.$e->getMessage()]));
    }
    
    echo json_encode([
        'success' => true,
        'csrf_token' => $_SESSION['csrf_token']
    ]);
}

/*==================================================================
=  ACTION: delete
==================================================================*/
function deleteRow(PDO $pdo, string $tbl, array $cfg)
{
    requireCsrf();

    $pkField = $cfg['primaryKey'];
    $pkVal   = $_POST[$pkField] ?? ($_GET['id'] ?? null);

    if ($pkVal === null) {
        http_response_code(400);
        exit(json_encode(['error' => "Missing primary key '{$pkField}'"]));
    }

    $stmt = $pdo->prepare("DELETE FROM {$tbl} WHERE {$pkField} = :pk");
    $stmt->execute([':pk' => $pkVal]);

    echo json_encode([
        'success' => true,
        'csrf_token' => $_SESSION['csrf_token']
    ]);
}

/*==================================================================
=  ACTION: create / insert
==================================================================*/
function createRow(PDO $pdo, string $tbl, array $cfg)
{
    requireCsrf();

    /* 1. Build whitelist of columns we are willing to insert.
     *    For simplicity we accept every POST field that:
     *      • exists in displayColumns,
     *      • is NOT the primary key,
     *      • and (for file upload) we special-case "picture".
     */
    $cols   = [];
    $params = [];
    
    // Special handling for products table - category_id is required
    if ($tbl === 'products') {
        // Default to category 1 if not specified
        $cols[] = 'category_id';
        $params[] = $_POST['category_id'] ?? 1;
        
        // Add created_by if we have a root user
        if (isset($_SESSION['root_id'])) {
            $cols[] = 'created_by';
            $params[] = $_SESSION['root_id'];
        }
    }
    
    foreach ($cfg['displayColumns'] as $col) {
        if ($col === $cfg['primaryKey']) continue;

        if ($col === 'picture' && isset($_FILES['imageName'])) {
            // Create directory if it doesn't exist
            $destDir = __DIR__ . '/../../images/products/';
            if (!is_dir($destDir)) {
                mkdir($destDir, 0775, true);
            }
            
            $ext     = pathinfo($_FILES['imageName']['name'], PATHINFO_EXTENSION) ?: 'png';
            $fname   = uniqid('p_', true) . '.' . $ext;
            
            if (!move_uploaded_file($_FILES['imageName']['tmp_name'], $destDir . $fname)) {
                http_response_code(500);
                exit(json_encode(['error' => 'Image upload failed']));
            }
            $cols[]   = $col;
            $params[] = $fname;
        } elseif (isset($_POST[$col])) {
            $cols[]   = $col;
            $params[] = $_POST[$col];
        }
    }

    if (!$cols) {
        http_response_code(400);
        exit(json_encode(['error' => 'No valid fields to insert']));
    }

    /* 2. Assemble & execute query */
    $placeholders = implode(', ', array_fill(0, count($cols), '?'));
    $colList      = implode(', ', $cols);
    $sql = "INSERT INTO {$tbl} ({$colList}) VALUES ({$placeholders})";

    $stmt = $pdo->prepare($sql);
    
    try {
        foreach ($params as $i => $val) {
            $stmt->bindValue($i + 1, $val);
        }
        $stmt->execute();
    } catch (PDOException $e) {
        if (isset($e->errorInfo[0]) && $e->errorInfo[0] === '23000') {
            if (preg_match("/for key '([^']+)'/", $e->errorInfo[2], $m)) {
                $field = $m[1];
                $msg   = ucfirst($field) . " already exists.";
            } else {
                $msg = 'Integrity constraint violation.';
            }
            http_response_code(409);
            exit(json_encode(['success'=>false, 'error'=>$msg]));
        }
        http_response_code(500);
        exit(json_encode(['success'=>false, 'error'=>'Database error: '.$e->getMessage()]));
    }

    echo json_encode([
        'success' => true, 
        'insert_id' => $pdo->lastInsertId(),
        'csrf_token' => $_SESSION['csrf_token']
    ]);
}

/**
 * Promote a customer to root by copying their email/username
 * and hashing the provided password.
 */
function grantRoot(PDO $pdo, string $tbl, array $cfg)
{
    requireCsrf();

    if ($tbl !== 'customers') {
        http_response_code(400);
        exit(json_encode(['error'=>'Can only grant root to customers']));
    }

    $custId   = $_POST['id'] ?? null;
    $password = $_POST['password'] ?? '';

    if (!$custId || !$password) {
        http_response_code(400);
        exit(json_encode(['error'=>'Missing user ID or password']));
    }

    // 1) fetch customer record
    $stmt = $pdo->prepare("SELECT email, username FROM customers WHERE customer_id = ?");
    $stmt->execute([$custId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        http_response_code(404);
        exit(json_encode(['error'=>'User not found']));
    }

    // 2) check they’re not already a root
    $chk = $pdo->prepare("SELECT 1 FROM roots WHERE email = ?");
    $chk->execute([$user['email']]);
    if ($chk->fetch()) {
        http_response_code(409);
        exit(json_encode(['error'=>'User is already a root admin']));
    }

    // 3) insert into roots
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $ins  = $pdo->prepare(
      "INSERT INTO roots (email, password, username) VALUES (?, ?, ?)"
    );
    $ins->execute([$user['email'], $hash, $user['username']]);

    echo json_encode([
      'success'    => true,
      'csrf_token' => $_SESSION['csrf_token']
    ]);
}

function deleteRootWithPassword(PDO $pdo, string $tbl, array $cfg)
{
    requireCsrf();

    if ($tbl !== 'roots') {
        http_response_code(400);
        exit(json_encode(['error'=>'Invalid table']));
    }

    $targetId = $_POST['id']       ?? null;
    $password = $_POST['password'] ?? '';

    if (!$targetId || !$password) {
        http_response_code(400);
        exit(json_encode(['error'=>'Missing parameters']));
    }

    // 1) fetch the target’s password hash
    $stmt = $pdo->prepare("SELECT password FROM roots WHERE root_id = ?");
    $stmt->execute([$targetId]);
    $hash = $stmt->fetchColumn();

    if (!$hash || !password_verify($password, $hash)) {
        http_response_code(403);
        exit(json_encode(['error'=>'Incorrect password for that account']));
    }

    // 2) optionally prevent self-delete if you like
    if ((int)$targetId === (int)$_SESSION['root_id']) {
        http_response_code(403);
        exit(json_encode(['error'=>"You cannot delete your own root account"]));
    }

    // 3) perform the deletion
    $del = $pdo->prepare("DELETE FROM roots WHERE root_id = ?");
    $del->execute([$targetId]);

    echo json_encode([
      'success'    => true,
      'csrf_token' => $_SESSION['csrf_token']
    ]);
}