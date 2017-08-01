<?php

/* @var $view \Nethgui\Renderer\Xhtml */
echo $view->header('username')->setAttribute('template', $T('Modify_header'));

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);

echo $view->fieldsetSwitch('sudo', 'enabled', $view::FIELDSETSWITCH_EXPANDABLE | $view::FIELDSETSWITCH_CHECKBOX)
    ->setAttribute('uncheckedValue', 'disabled')
    ->insert($view->textArea('sudoCommands', $view::LABEL_ABOVE)->setAttribute('dimensions', '5x30'));


echo ($view->fieldsetSwitch('panelsDelegation','enabled', $view::FIELDSETSWITCH_EXPANDABLE | $view::FIELDSETSWITCH_CHECKBOX)
    ->setAttribute('uncheckedValue', 'disabled')
    ->insert($view->fieldsetSwitch('AdminAllPanels', 'enabled')->setAttribute('label', $T('AdminAllPanels_label')))
    ->insert($view->fieldsetSwitch('AdminAllPanels', 'disabled', $view::FIELDSET_EXPANDABLE)
    ->setAttribute('label', $T('AdminEachPanel_label'))
    ->insert($view->selector('AdminPanels', $view::SELECTOR_MULTIPLE)->setAttribute('label', $T('ListOfPanels_label'))))
);

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);

$checkboxJson = json_encode((string) $view->checkBox('CheckAll','disabled'));
$checkboxId = $view->getUniqueId('CheckAll');
$categoriesTarget = $view->getClientEventTarget('AdminPanels');
$view->includeJavascript(" 
(function ( $ ) {
    $(document).ready(function() {
        $('.$categoriesTarget').before($checkboxJson);
        $('.$categoriesTarget').css( 'padding-left', '.8em' );
        $('#$checkboxId').click(function() {
            $('.$categoriesTarget :checkbox').not(this).prop('checked', this.checked);
        });
        $('.$categoriesTarget').on('nethguiupdateview', function (e, value) {
            if ($.isArray(value) && value.length < 2){
                $('#$checkboxId').parent().hide();
            } else {
                $('#$checkboxId').parent().show();
            }
        });
    });
})( jQuery );
");
