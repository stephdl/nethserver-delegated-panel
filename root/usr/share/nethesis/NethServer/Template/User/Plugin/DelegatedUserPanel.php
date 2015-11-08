<?php
echo $view->checkBox('AdminAllPanels','enabled')->setAttribute('uncheckedValue', 'disabled');
echo $view->selector('AdminPanels', $view::SELECTOR_MULTIPLE);
