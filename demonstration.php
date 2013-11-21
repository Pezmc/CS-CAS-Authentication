<?php
/**
 * This is an example of how to authenticate a user with a University username
 * and password.
 * 
 * It required the instillation of the file Authenticator.php in the same directory.
 * 
 * @todo The developer must edit thsi file to define the constant DEVELOPER_URL.
 * 
 * @author Iain Hart (iain@cs.man.ac.uk)
 * @date 1st November 2013
 */

// @todo Replace the following defined constant with the URL which runs the program
// requiring authentication.
define ("DEVELOPER_URL", "http://studentnet.cs.manchester.ac.uk/authenticate/demonstration.php");

// Define the location of the service on the Computer Science server.
define("AUTHENTICATION_SERVICE_URL", "http://studentnet.cs.manchester.ac.uk/authenticate/");

// Load the Authenticator class.
require_once("Authenticator.php");

// Start a php session to store variables. This will be used to hold the ticket
// issued to the user so that when the user returns from CAS we know that
// we are interacting with the same user.
session_start();

// Validate the user.
Authenticator::validateUser();

// To invalidate a user uncomment the following.
// Authenticator::invalidateUser();

?>

<html>
    <head>
        <title>Authentication with University of Manchester</title>
    </head>
    <body>
        <h1>Demonstration of authentication with University of Manchester</h1>
        
        <h2>Welcome</h2>
        <p>
            You have successfully authenticated. The details returned by the
            authentication service are as follows.
        </p>
        
        <ul>
            <li>
                You authenticated at 
                    <?php
                        $timestamp = Authenticator::getTimeAuthenticated();
                        echo date("l jS F Y H:i:s", $timestamp);
                    ?>
            </li>
            
            <li>
                Your username is 
                    <?php
                        echo Authenticator::getUsername();
                    ?>
            </li>
            
            <li>
                Your full name is 
                    <?php
                        echo Authenticator::getFullName();
                    ?>
            </li>
        </ul>
        
        <p>
            This page will tell you how to set up the same authentication
            for yourself.
        </p>
        
        <h3>Overview</h3>
        <p>
            This authentication technique uses 
            <a href="https://wiki.jasig.org/display/CAS/Home">CAS</a>.
        </p>
        <p>
            The purpose of this authentication technique is to send the user to 
            some authentication service which authenticates the user without the 
            developer seeing the user's username or password.
        </p>
        <p> 
            Assuming the authentication is valid the service
            redirects the user along with some login credentials. In this case 
            a University of Manchester username and the user's full name are
            returned.
        </p>
        <p>
            For the developer to be sure that the same user has been returned
            a unique identifier (ticket) is issued to the user to take to the
            authentication service. To validate that the same user has returned
            the developer keeps a copy of the ticket and checks they match upon
            the user's return.
        </p>
        
        <h3>PHP</h3>
        <p>
            This page provides an example using PHP of how to authenticate that 
            a user has a University of Manchester username and password.
        </p>
        <p>
            The developer's PHP configuration will require session variables
            to be enabled.
        </p>
        
        <h4>API</h4>
        <p>
            The API is demonstrated for the developer at the top of this file
            where the user's details are displayed.
        </p>
        <p>
            The one exception which is not demonstrated is if the developer 
            needs to invalidate a user's authentication then 
            use: Authenticator::invalidateUser();
        </p>

        <h4>Download PHP files</h4>
        <p>
            The developer can 
            <a href="CS_CAS_Authentication.zip">download this php example</a> 
            in this zip.
        </p>
        
        <h3>Other languages</h3>
        <p>
            To build an authentication mechanism with a different language
            the developer needs to send the user to 
            <?=AUTHENTICATION_SERVICE_URL?>
            with two parameters.
        </p>
        <ol>
            <li>
                url - which is the URL to which the user is returned after 
                      authentication. In other words it is the URL running the 
                      developer's program.
                     
            </li>
            
            <li>
                csticket - a unique identifier so that the developer knows the 
                           same user has been returned after authentication.
            </li>
                
        </ol>
        <h4>Important</h4>
        <p>
            Only use the ticket once and only use the GET parameters once to 
            record the user's name and username.
        </p>
        <p>
            Because the ticket and details are returned from the authentication
            service as a GET parameter the user could edit them if they are
            used more than once.
        </p>
        
    </body>
</html>

<?php
// End our session.
session_end();
?>