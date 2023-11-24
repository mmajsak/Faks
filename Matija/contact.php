<?php
print'
<h1>Contact Form</h1>
            <div id="Contact">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.7945292036247!2d15.969800499999998!3d45.79534399999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d71944949203%3A0x7493c811b44c4bd9!2sVrbik%208%2C%2010000%2C%20Zagreb!5e0!3m2!1shr!2shr!4v1697025037800!5m2!1shr!2shr" width="900" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <form action="">
                    <label for="fname">First Name *</label>
                    <input type="text" id="fname" name="firstname" placeholder="Your name.." required>

                    <label for="lname">Last Name *</label>
                    <input type="text" id="lname" name="lastname" placeholder="Your last name.." required>
                    
                    <label for="lname">Your E-mail *</label>
                    <input type="email" id="email" name="email" placeholder="Your e-mail.." required>

                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="">Please select</option>
                        <option value="BE">Belgium</option>
                        <option value="HR" selected>Croatia</option>
                        <option value="LU">Luxembourg</option>
                        <option value="HU">Hungary</option>
                    </select>

                    <label for="subject">Subject</label>
                    <textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea>

                    <input type="submit" value="Submit">
                </form>
            </div>';
?>
