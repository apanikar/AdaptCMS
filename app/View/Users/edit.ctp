<script>
$(document).ready(function(){
    $("#UserEditForm").validate();

    $("#UserPasswordConfirm").rules("add", {
        required: false,
        equalTo: "#UserPassword",
        messages: {
            equalTo: "Passwords do not match"
        }
    });

    $("#UserEmail").rules("add", {
        required: true,
        email: true
    });

    $(".security-question").on('change', function() {
        var id = $(this).attr('id');

        if ($(this).val()) {
            $("div#" + id).show();
        } else {
            $("div#" + id).hide();
        }

        $.each($(".security-question"), function(i, row) {
            if ($(this).attr('id') != id) {
                var new_id = $(this).attr('id');
                
                $.each($("#UserSecurityQuestionHidden option"), function(key, val) {
                    var find = $("form").find($(".security-question option[value='" + $(this).val() + "']:selected")).val();
                    
                    if ($(this).val() == find && find) {
                        $("#" + new_id + " option[value='" + $(this).val() + "']:not(:selected)").remove();
                    } else {
                        if ($("#" + new_id + " option[value='" + $(this).val() + "']").length == 0) {
                            $("#" + new_id).append("<option value='" + $(this).val() + "'>" + $(this).html() + "</option>");
                        }
                    }
                });
            }
        });
    });

	if ($(".security-question").length > 0) {
		$.each($(".security-question"), function() {
			if ($(this).val()) {
				$(this).parent().next().show();
			}
		});
	}
});
</script>

<h1>Edit Account</h1>

<?= $this->Form->create('User', array('class' => 'well')) ?>

<?php    
	echo $this->Form->input('username', array(
		'class' => 'required', 
		'disabled'
	));
	echo $this->Form->input('password', array(
		'type' => 'password', 
		'label' => 'New Password?', 
		'value' => ''
	));
	echo $this->Form->input('password_confirm', array(
		'type' => 'password',
		'value' => ''
	));
	echo $this->Form->input('email', array(
		'type' => 'text', 
		'class' => 'required'
	));

	echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
    echo $this->Form->hidden('id');
?>

<?php if (!empty($this->request->data['SecurityQuestions']['SettingValue']['data'])): ?>
    <?php if (!empty($security_options)): ?>
        <?= $this->Form->input('security_question_hidden', array(
                'options' => $security_options,
                'label' => false,
                'div' => false,
                'style' => 'display:none'
        )) ?>
        <?php for($i = 1; $i <= $this->request->data['SecurityQuestions']['SettingValue']['data']; $i++): ?>
            <?= $this->Form->input('Security.'.$i.'.question', array(
                    'empty' => '- choose -', 
                    'class' => 'required security-question', 
                    'options' => $security_options,
                    'label' => 'Security Question '.$i
            )) ?>
            <div id="Security<?= $i ?>Question" style="display: none">
                <?= $this->Form->input('Security.'.$i.'.answer', array(
                        'class' => 'required',
                        'label' => 'Security Answer '.$i
                )) ?>
            </div>
        <?php endfor ?>
    <?php endif ?>
<?php endif ?>

<?= $this->Form->input('theme_id', array('label' => 'Theme', 'empty' => '- Choose Theme -')) ?>

<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
)) ?>