<?php

class AppError extends ErrorHandler {
 
 function _outputMessage($template) {
  $this->controller->layout = 'missing_layout'; // /app/views/layouts/error_template.ctp
  parent::_outputMessage($template);
 }

}

?>