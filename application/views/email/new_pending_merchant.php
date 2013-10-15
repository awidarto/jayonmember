<html>
    <body>
        <p>
            Hello <?php print $fullname;?>,
        </p>
        <p>
            You are now registered as a merchant at Jayon Express. Please verify the details you submitted below.
        </p>
        <p>
            Your current status is <strong>PENDING MERCHANT</strong>.
        </p>
        <p>
            Name: <?php print $fullname;?><br />
            E-mail address: <?php print $email;?><br />
            Company name: <?php print $fullname;?><br />
            Business address: <?php print $mc_address;?><br />
            Warehouse address: <?php print $mc_warehouse;?><br />
            Phone: <?php print $phone;?><br />
            Payment info:<br />
            <?php print $bank;?><br />
            <br />
        </p>
        <p>
            You may use Jayon Express COD service on your online store, after Jayon Admin activated your status into active merchant.<br />
            Our admin personnel will contact you upon your merchant account activation<br />
            <br />
            For instructions on how to implement Jayon Express COD payment system, you may refer to this page http://www.jayonexpress.com/merchant/instructions
        </p>
        <p>
            Kindly keep this e-mail for your record.
        </p>
        <p>
            Thank you,<br />
            Jayon Express team
        </p>
    </body>
</html>