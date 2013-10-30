<!DOCTYPE html>
<html>
  <head>
    <?php
    $view->getHeaders();
    $view->getTitle();
    $view->getAllCss();
    $view->getAllJS();
    ?>
  </head>
  <body>
    <div>
      <div id="top">
        <div title="WebAdvice - Filip Koblanski - Vortal wiedzy o projektach webowych" id="header_content"></div>
        <div id="socket_LoginPanel">
          <div class="component_label">
            <?=$login_panel; ?>
          </div>
          <div class="component_content"></div>

        </div>

      </div>
      <div id="content">
        <div id="path_field">

        </div>
        <div id="page_label">

        </div>
        <div class="strike">

        </div>

      </div>
      <div style="clear: both;"></div>

    </div>

  </body>

</html>
