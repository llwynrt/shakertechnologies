<?php 
    //display if the email was send or not
    echo $this->session->flashdata('msg'); 

    $attributes = array("class" => "form-horizontal", "name" => "contact");
    echo form_open("contact", $attributes);

?>
<fieldset>
    <legend>Contactez-nous !</legend>
    <p>Laissez-nous un message, nous vous recontacterons dès que possible.</p>

    <div class="form-group">
			<label class="sr-only" for="name">Votre nom</label>
            <input class="form-control" name="name" placeholder="Votre nom" type="text" value="<?php echo set_value('name'); ?>" />
            <span class="text-danger"><?php echo form_error('name'); ?></span>
    </div>

    <div class="form-group">
			<label class="sr-only" for="email">Votre email</label>
            <input class="form-control" name="email" placeholder="Votre email" type="text" value="<?php echo set_value('email'); ?>" />
            <span class="text-danger"><?php echo form_error('email'); ?></span>
    </div>

    <div class="form-group">
			<label class="sr-only" for="subject">Sujet</label>

            <input class="form-control" name="subject" placeholder="Sujet" type="text" value="<?php echo set_value('subject'); ?>" />
            <span class="text-danger"><?php echo form_error('subject'); ?></span>
    </div>

    <div class="form-group">
			<label class="sr-only" for="message">Votre message</label>

            <textarea class="form-control" name="message" rows="4" placeholder="Votre message"><?php echo set_value('message'); ?></textarea>
            <span class="text-danger"><?php echo form_error('message'); ?></span>
    </div>

    <div class="form-group">
            <input name="submit" type="submit" class="btn btn-turquoise" value="Envoyer" />
    </div>
</fieldset>

<?php 
    echo form_close(); 
?>
