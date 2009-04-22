    <div id="register">
        <h2>Register new User</h2>

        To register please enter your name [realname] your nick [loginname] and a valid email adress!
        <br />
        After successful registration you will receive an awnser soon after Administration has
        authoriziesed your registration.
        <br />
        <br />

        <div class="error">
        {regErrors}
        </div>

        <form method="post" action="index.php?action=register" >
        <fieldset><legend>{msgUserData}</legend>
        <div class="required">
            <label for="lastname">{lastname} {name_errorRegistration}</label>
            <input type="textfield" name="lastname" id="lastname" value="{lastname_value}" class="inputfield" />
        </div><br />
        <div class="required">
            <label for="loginname">{loginname} {login_errorRegistration}</label>
            <input type="textfield" name="loginname" id="loginname" value="{loginname_value}" class="inputfield" />
        </div><br />
        <div class="required">
            <label for="email">{email} {email_errorRegistration}</label>
            <input type="textfield" name="email" id="email" value="{email_value}" class="inputfield" />
        </div>
        </fieldset>

        {captchaFieldset}

        <br />
        <input type="submit" name="" value="{submitRegister}" />
        </form>
    </div>