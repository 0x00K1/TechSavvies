<?php
$current_page = $_SERVER['REQUEST_URI'];
$hide_home_on = ['/', '/logout.php'];
$hide_account_on = ['/logout.php'];
$hide_cart_on = ['/logout.php', '/categories/cart'];
$hide_root_on = ['/root', '/root/'];
?>
<header>
  <div class="logo">
    <a href="/">
      <img src="/assets/images/LogoName.webp" alt="TechSavvies Logo" />
    </a>
  </div>
  <nav>
    <ul>
      <?php 
       // Check if the current URL starts with '/categories/checkouts'
       $is_checkouts = (strpos($current_page, '/categories/checkouts') === 0);
      if (
        !$is_checkouts &&
        !in_array($current_page, $hide_cart_on) &&
        (!isset($_SESSION['is_root']) || $_SESSION['is_root'] !== true)
      ): ?>
        <li id="cartLink">
          <div class="cart-control" id="cartIconWrapper">
            <svg id="cartIcon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g id="cartIcon" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z" stroke="#ffffff" stroke-width="1.5"></path>
                <path d="M16.5 18.0001C17.3284 18.0001 18 18.6716 18 19.5001C18 20.3285 17.3284 21.0001 16.5 21.0001C15.6716 21.0001 15 20.3285 15 19.5001C15 18.6716 15.6716 18.0001 16.5 18.0001Z" stroke="#ffffff" stroke-width="1.5"></path>
                <path d="M2 3L2.26121 3.09184C3.5628 3.54945 4.2136 3.77826 4.58584 4.32298C4.95808 4.86771 4.95808 5.59126 4.95808 7.03836V9.76C4.95808 12.7016 5.02132 13.6723 5.88772 14.5862C6.75412 15.5 8.14857 15.5 10.9375 15.5H12M16.2404 15.5C17.8014 15.5 18.5819 15.5 19.1336 15.0504C19.6853 14.6008 19.8429 13.8364 20.158 12.3075L20.6578 9.88275C21.0049 8.14369 21.1784 7.27417 20.7345 6.69708C20.2906 6.12 18.7738 6.12 17.0888 6.12H11.0235M4.95808 6.12H7" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"></path>
              </g>
            </svg>
          </div>
        </li>
      <?php endif; ?>

      <?php if (
        !in_array($current_page, $hide_account_on) &&
        (!isset($_SESSION['is_root']) || $_SESSION['is_root'] !== true)
      ): ?>
        <li id="accountLink">
          <svg id="accountIcon" viewBox="0 0 24 24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <defs>
                <style>.cls-1{fill:none;stroke:#ffffff;stroke-miterlimit:10;stroke-width:1.91px;}</style>
              </defs>
              <circle class="cls-1" cx="12" cy="7.25" r="5.73"></circle>
              <path class="cls-1" d="M1.5,23.48l.37-2.05A10.3,10.3,0,0,1,12,13h0a10.3,10.3,0,0,1,10.13,8.45l.37,2.05"></path>
            </g>
          </svg>
        </li>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['is_root']) && $_SESSION['is_root'] === true &&
        !in_array($current_page, $hide_root_on)): ?>
        <li id="rootLink">
          <a href="/root">
            <svg fill="#ffffff" id="rootIcon" viewBox="-2.4 -2.4 28.80 28.80" role="img" xmlns="http://www.w3.org/2000/svg" transform="rotate(0)">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M10.857 14.458s.155.921-.034 2.952c-.236 2.546.97 6.59.97 6.59s1.645-4.052 1.358-6.67c-.236-2.152.107-2.904.034-2.803-1.264 1.746-2.328-.069-2.328-.069zm3.082 2.185c.206 1.591-.023 2.462-.32 4.164-.15.861 3.068-2.589 4.302-4.645.206-.343-1.18 1.337-2.55.137-.952-.832-1.115-1.085-1.854-1.808-.249-.244.277 1.014.423 2.151zm-3.512-2.025c-.739.723-.903.976-1.853 1.808-1.371 1.2-2.757-.48-2.551-.137 1.234 2.057 4.452 5.506 4.302 4.645-.297-1.703-.526-2.574-.32-4.164.147-1.137.673-2.395.423-2.15zm3.166-2.839c1.504.434 2.088 2.523 3.606 2.781.314.053.667.148 1.08.128.77-.037 1.743-.472 3.044-2.318.385-.546-.955 3.514-4.313 3.563-2.46.036-2.747-2.408-4.387-2.482-.592-.027-.629-1.156-.629-1.156s.706-.774 1.598-.517zm-3.186-.012c-1.504.434-2.088 2.523-3.606 2.781-.314.053-.667.148-1.08.128-.77-.037-1.743-.472-3.044-2.318-.385-.546.955 3.514 4.313 3.563 2.46.036 2.747-2.408 4.387-2.482.592-.027.629-1.156.629-1.156s-.706-.774-1.598-.517zm5.626-.02c1.513 1.146 1.062 2.408 1.911 2.048 2.86-1.212 2.36-7.434 2.128-6.682-1.303 4.242-4.143 4.48-6.876 2.528-.534-.38 1.985 1.46 2.837 2.105zm-5.24-2.106C8.06 11.592 5.22 11.355 3.917 7.113c-.231-.752-.731 5.47 2.128 6.682.849.36.398-.902 1.91-2.048.853-.646 3.372-2.486 2.838-2.105zm5.526.584c3.3-.136 3.91-5.545 3.65-4.885-1.165 2.963-5.574 1.848-5.995 3.718-.083.367.747 1.233 2.345 1.167zm-6.304-1.167c-.421-1.87-4.831-.755-5.995-3.718-.26-.66.35 4.75 3.65 4.885 1.599.066 2.428-.8 2.345-1.167zm3.753-.824s1.794-.964 3.33-1.384c1.435-.393 2.512-1.359 2.631-2.38.09-.76-1.11-2.197-1.11-2.197s-.84 2.334-1.945 3.501c-1.2 1.27-.745 1.1-2.906 2.46zm-6.453-2.46c-1.104-1.167-1.945-3.5-1.945-3.5S4.17 3.708 4.26 4.47c.12 1.021 1.196 1.987 2.63 2.38 1.537.421 3.331 1.384 3.331 1.384-2.162-1.36-1.705-1.19-2.906-2.46zm6.235 2.312c1.943-1.594 2.976-3.673 4.657-5.949.317-.429-1.419-1.465-2.105-1.533-.686-.068-1.262 2.453-1.327 3.936-.059 1.354-1.486 3.761-1.224 3.547z"></path>
              </g>
            </svg>
          </a>
        </li>
      <?php endif; ?>

      <?php if (!in_array($current_page, $hide_home_on)): ?>
        <li id="homelink">
          <a href="/">
            <img src="/assets/icons/home.svg" alt="Home" id="homeIcon">
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
<!-- Authentication Modal-->
<?php require_once __DIR__ . '/../../assets/php/auth.php'; ?>