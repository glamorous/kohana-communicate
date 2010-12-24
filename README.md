This Email-wrapper class for Kohana 3.0 doesn't have a proper documentation yet. When there is some spare time, I will write the guide. The code is "documentated" inside the class.

How to use:

    $mail = Communicate::factory('postmark');

    $mail->setup()
        ->addTo('myemail@website.com', 'Your name')
        ->addFrom('me@example.com')
        ->addMessage($htmlbody, $textbody)
        ->addSubject('testemail');
        
    if($mail->send())
    {
        //success
    }