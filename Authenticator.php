<?php
/**
 * This class will enable the developer to authenticate that their users'
 * have a valied University of Manchester account.
 * 
 * @author Iain Hart (iain@cs.man.ac.uk)
 * @date 1st November 2013
 */

class Authenticator
{
    /**
     * A static function to validate that a user has a University of Manchester
     * account. If the user is not authenticated the program will exit.
     */

    public static function validateUser()
    {
        // If the user is already authenticated return.
        if (self::isAuthenticated())
            return;
        
        // Else if the GET parameter csticket is empty this is a new user who we 
        // need to send for authentication.
        else if (empty($_GET["csticket"]))
            self::sendForAuthentication();

        
        // Else if the GET parameter csticket is populated check it matches
        // the csticket gaven to the user before authentication.
        else if ($_GET["csticket"] != $_SESSION["csticket"])
            self::rejectUser();

        else
            self::recordAuthenticatedUser();
    }
    
   
    /**
     * A static function to determine whether a user is already authenticated.
     * @return boolean (true if authenticated, false if not)
     */
    
    private static function isAuthenticated()
    {
        // When a useris authenticated the $_SESSION["authenticated"] is 
        // poplulated with a timestamp. If a numerical value is held return true
        $authenticatedtimestamp = self::getTimeAuthenticated();
        if (!empty($authenticatedtimestamp) && is_numeric($authenticatedtimestamp))
            return true;
        
        // Else the user is not already authenticated so return false;
        else
            return false;

    }
    
    /**
     * A static function to send a user to the authentication service.
     */
    private static function sendForAuthentication()
    {
        // Generate a unique ticket.
        $csticket = uniqid();
    
        // Save the ticket so we can confirm the same users is returning from 
        // the authentication service.
        $_SESSION["csticket"] = $csticket;
    
        // Send the user to the School of Computer Science's server to validate.
        // Append to the url the GET parameters 'url' which tells the 
        // authentication service where to return and append the csticket which 
        // will be used to confirm that the same user is returning.
        $url = AUTHENTICATION_SERVICE_URL . "?url=" . DEVELOPER_URL . "&csticket=$csticket";
        header("Location: $url");
        exit;
    }
    
    /**
     * A static function to reject a user who has failed to authenticate.
     */
    
    private static function rejectUser()
    {
        exit("<h1>Authentication failure</h1>You have failed to authenticate");
    }
    
  
    /**
     * A static function to record that a user is authenticated.
     */
    private static function recordAuthenticatedUser()
    {
        // Record the time authenticated.
        $_SESSION["authenticated"] = mktime();
        
        // Record the user's username.
        $_SESSION["username"] = $_GET["username"];
        
        // Record the user's full name.
        $_SESSION["fullname"] = $_GET["fullname"];
    }    
    
    /**
     * A static function to get the timestamp when the user authenticated.
     * @return string
     */
    
    public static function getTimeAuthenticated()
    {
        return $_SESSION["authenticated"];
    }

    /**
     * A static function to get the user's username as returned by the 
     * authentication service.
     * @return string
     */    
    
    public static function getUsername()
    {
        return $_SESSION["username"];
    }

    /**
     * A static function to get the user's full name as returned by the 
     * authentication service.
     * @return string
     */    
    
    public static function getFullName()
    {
        return $_SESSION["fullname"];
    }
    
    /**
     * A static function to invalidate a user. This function will remove the
     * data from the global variable $_SESSION
     */
    
    public static function invalidateUser()
    {
        unset($_SESSION);
        session_destroy();
    }
    
}

?>