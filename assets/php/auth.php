<div class="auth-modal" id="authModal">
  <div class="auth-modal-content">
    <a class="close" id="closeModal">&times;</a>
    
    <!-- Step 1: Enter Email -->
    <div class="auth-step" id="authStep1">
      <img src="/TechSavvies/assets/images/logo.png" alt="Site Logo" />
      <p>Sign in/up for savvy shopping</p>
      <input type="email" id="authEmail" placeholder="Email Address" />
      <button id="sendOtpBtn">Send OTP</button>
    </div>

    <!-- Step 2: Enter OTP -->
    <div class="auth-step" id="authStep2" style="display: none;">
      <h2>Verify It's You</h2>
      <p>We've sent a code to your email</p>
      <input type="text" id="authOTP" maxlength="10" />
      <button id="verifyOtpBtn">Verify & Continue</button>
    </div>
  </div>
</div>
