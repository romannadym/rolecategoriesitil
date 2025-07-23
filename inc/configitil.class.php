<?php
class PluginRolecategoriesitilConfigitil extends CommonDBTM {

  static private $_instance = NULL;
  static $rightname         = 'ITILCategory';
  function getName($with_comment=0) {
    return _n('Profile', 'Profile', $with_comment);
  }

  static function getInstance() {

    if (!isset(self::$_instance)) {
      self::$_instance = new self();
      if (!self::$_instance->getFromDB(1)) {
        self::$_instance->getEmpty();
      }
    }
    return self::$_instance;
  }

  static function showConfigForm($item) {
    if (Session::haveRight("itilcategory", READ)) {
      // Создаем экземпляр класса ITILCategory
      $ITILCategory = new ITILCategory();
      // Проверяем, установлен ли ID в GET-запросе
      if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Загружаем ITILCategory по его ID
        $ITILCategory->getFromDB($_GET['id']);

        // Получаем ID ITILCategory
        $ITILCategory_ID = $ITILCategory->getID();
      } else {
        echo "ITILCategory ID is not set or invalid.";
        return false;
      }
    } else {
      echo "You do not have permission to view this ITILCategory.";
      return false;
    }

    $config =  new PluginRolecategoriesitilConfig();
    $config->showFormHeader(['no_header'=>true, 'target'=>'/plugins/rolecategoriesitil/front/configitil.form.php']);
    echo Html::hidden('_glpi_csrf_token', ['value' => Session::getNewCSRFToken()]);
    echo "<thead><tr class='tab_bg_1'>";
    echo "<th width='2%'>".__('ID')."</th>";
    echo "<th   width='10%'>"._n('Наименование', 'Наименование', 0)."</th>";
    echo "<th  class='center' width='3%'>".__('Visibility')."</th>";
    echo "</tr></thead>";
    echo Html::hidden('itilcategory_id', ['value' => $ITILCategory_ID]);
    // Убедитесь, что пользователь имеет права на просмотр категорий ITIL
    if (Session::haveRight('profile', READ)) {
      // Создаем экземпляр класса ItilCategory
      $Profile = new Profile();
      // Получаем список категорий
      // Метод find возвращает массив категорий, удовлетворяющих условиям
      $Profiles = $Profile->find();
      //file_put_contents(GLPI_ROOT.'/tmp/buffer.txt',PHP_EOL.PHP_EOL. json_encode($categories,JSON_UNESCAPED_UNICODE), FILE_APPEND);
      // Перебор и отображение категорий
      echo "<tr class='tab_bg_1 select-all-row' data-id='0'>"; // Добавили специальный класс select-all-row
      echo "<td>-</td>";
      echo "<td>Все профили</td>";
      echo "<td class='center' width='3%'>";
      echo Html::hidden('select_all', ['value' => 0]);
      echo Html::getCheckbox([
          'name'    => 'select_all',
          'checked' => false,
          'id'      => 'select_all_checkbox' // Добавили ID для чекбокса
      ]);
      foreach ($Profiles as $id => $data) {
          $fields = $config->find(['profile_id'=>$id,'itilcategory_id'=>$ITILCategory_ID]);
          $fields = array_shift($fields);
          $name = $data['name'];
          echo "<tr class='tab_bg_1 clickable-row' data-id='$id'>";
          echo "<td>$id</td>";
          echo "<td>".Html::hidden('profile_id[]', ['value' => $data['id']]).__("$name", "profile")."</td>";

          echo "<td class='center' width='3%'>";
          // Добавляем скрытое поле с значением 0 перед чекбоксом
          echo Html::hidden("active[$id]", ['value' => 0]);
          Dropdown::showYesNo("active[$id]", $fields['active'], -1, ['use_checkbox'=>true]);
          echo "</td>
          </tr>";
      }
      echo "<tr class='center'><td colspan='4'>".Html::submit(__('Keep'), [
        'name'  => 'update',
        'class' => 'btn btn-primary mt-2'
        ])."</td></tr>";
      echo "</form>";
    } else {
      echo "You do not have permission to view Profiles.";
    }

    echo <<<SCRIPT
    <script type="text/javascript">
    $(document).ready(function() {
        // Добавляем стили через JS
        $("head").append(`<style>
            .clickable-row, .select-all-row { cursor: pointer; }
            .clickable-row:hover, .select-all-row:hover { background: #f5f5f5; }
        </style>`);

        // Функция проверки состояния всех чекбоксов
        function checkAllCheckboxes() {
            var allCheckboxes = $("input[type=checkbox][name^='active[']");
            var checkedCount = allCheckboxes.filter(":checked").length;
            var allChecked = (allCheckboxes.length > 0) && (checkedCount === allCheckboxes.length);

            $("#select_all_checkbox").prop("checked", allChecked);
        }

        // Обработка клика по строке "Все категории"
        $(document).on("click", ".select-all-row", function(e) {
            if ($(e.target).is("input, label")) return;

            var checkbox = $("#select_all_checkbox");
            checkbox.prop("checked", !checkbox.prop("checked"));
            toggleAllCheckboxes(checkbox[0]);
        });

        // Обработка кликов по обычным строкам
        $(document).on("click", ".clickable-row", function(e) {
            if ($(e.target).is("input, label, a, button, select, .select2")) return;

            var checkbox = $(this).find("input[type=checkbox][name^='active[']");
            if (checkbox.length) {
                checkbox.prop("checked", !checkbox.prop("checked")).trigger("change");
            }
        });

        // Функция для выделения всех чекбоксов
        window.toggleAllCheckboxes = function(source) {
            var isChecked = $(source).prop("checked");
            $("input[type=checkbox][name^='active[']").prop("checked", isChecked).trigger("change");
        };

        // Синхронизация и проверка состояния при изменении любого чекбокса
        $(document).on("change", "input[type=checkbox][name^='active[']", function() {
            var name = $(this).attr("name");
            $("input[type=hidden][name='" + name + "']").val($(this).is(":checked") ? 1 : 0);
            checkAllCheckboxes(); // Проверяем состояние всех чекбоксов
        });

        // Проверить состояние при загрузке страницы
        checkAllCheckboxes();
    });
    </script>
    SCRIPT;

    return false;
  }

  function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

    if ($item->getType()=='ITILCategory') {
      return self::getName();
    }
    return '';
  }


  static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

    if ($item->getType()=='ITILCategory') {
      self::showConfigForm($item);
    }
    return true;
  }

  static function updated($post)
  {
      global $DB;
      $itilcategory_id = $post['itilcategory_id'];

      // Проверяем, есть ли данные для обработки
      if (!isset($post['profile_id'])) {
          return true;
      }

      foreach ($post['profile_id'] as $key => $value) {
          // Получаем значение active для текущего itilcategory_id
          $active = isset($post['active'][$value]) ? $post['active'][$value] : 0;

          $DB->update(
              'glpi_plugin_rolecategoriesitil_configs',
              [
                  'active' => $active
              ],
              [
                  'profile_id' => $value,
                  'itilcategory_id' => $itilcategory_id
              ]
          );
      }
      return true;
  }

}
