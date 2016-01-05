            <?php echo $this->session->flashdata('msg'); ?>

            <?php $attributes = array("class" => "form-horizontal", "name" => "contact");
            echo form_open("contact", $attributes);?>
            <fieldset>
            <legend>Contactez-nous !</legend>
            <p>Laissez-nous un message, nous vous recontacterons dès que possible.</p>
            <div class="form-group">

                <div class="col-md-12">
                    <input class="form-control" name="name" placeholder="Votre nom" type="text" value="<?php echo set_value('name'); ?>" />
                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                </div>
            </div>

            <div class="form-group">

                <div class="col-md-12">
                    <input class="form-control" name="email" placeholder="Votre email" type="text" value="<?php echo set_value('email'); ?>" />
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>
            </div>

            <div class="form-group">

                <div class="col-md-12">
                    <input class="form-control" name="subject" placeholder="Sujet" type="text" value="<?php echo set_value('subject'); ?>" />
                    <span class="text-danger"><?php echo form_error('subject'); ?></span>
                </div>
            </div>

            <div class="form-group">

                <div class="col-md-12">
                    <textarea class="form-control" name="message" rows="4" placeholder="Votre message"><?php echo set_value('message'); ?></textarea>
                    <span class="text-danger"><?php echo form_error('message'); ?></span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <input name="submit" type="submit" class="btn btn-turquoise" value="Send" />
                </div>
            </div>
            </fieldset>
            <?php echo form_close(); ?>
