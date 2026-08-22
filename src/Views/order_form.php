<form action="/create-order" method="POST">
    <div class="container">
        <h1>Order form</h1>
        <?php if(isset($errors['cart'])): ?>
            <label style="color: crimson"><?php echo $errors['cart']; ?></label>
        <?php endif; ?>
        <hr>

        <label for="name"><b>Name</b></label>
        <?php if(isset($errors['contact_name'])): ?>
        <label style="color: crimson"><?php echo $errors['contact_name']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter contact name" name="contact_name" id="contact_name">

        <label for="email"><b>Contact phone </b></label>
        <?php if(isset($errors['contact_phone'])): ?>
        <label style="color: crimson"><?php echo $errors['contact_phone']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter Contact phone" name="contact_phone" id="contact_phone" >

        <label for="psw"><b>Address</b></label>
        <?php if(isset($errors['address'])): ?>
        <label style="color: crimson"><?php echo $errors['address']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter address" name="address" id="address" >

        <label for="psw-repeat"><b>Comment</b></label>
        <?php if(isset($errors['comment'])): ?>
        <label style="color: crimson"><?php echo $errors['comment']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Repeat comment" name="comment" id="comment" >
        <hr>

        <button type="submit" class="orderbtn">Оформить заказ</button>
    </div>

    <div class="container signin">
        <p>Already have an account? <a href="/login">Sign in</a>.</p>
    </div>
</form>

<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .orderbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .orderbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>
