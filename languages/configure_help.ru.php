<?php
/**
 * Russian Language file for PhpGedView.
 *
 * Modifications Copyright (c) 2010 Greg Roach
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package PhpGedView
 * @author Eugene Fedorov
 * @author Natalia Anikeeva
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

//-- CONFIGURE FILE MESSAGES
$pgv_lang["configure"]			= "Configure PhpGedView";
$pgv_lang["default_user"]		= "Создать администратора";
$pgv_lang["about_user"]			= "Сначала Вы должны создать администратора. Администратор имеет право на настройку корректировки, личных данных, просмотр данных и создание пользователей.";
$pgv_lang["add_user"]			= "Добавить нового пользователя";
$pgv_lang["current_users"]		= "Существующие пользователи";
$pgv_lang["leave_blank"]		= "Оставьте поле для ввода пароля пустым если Вы хотите сохранить существующий пароль.";
$pgv_lang["other_theme"]		= "Другое, введите";
$pgv_lang["performing_update"]		= "Выполнить изменения.";
$pgv_lang["config_file_read"]		= "Читать файл конфигурации.";
$pgv_lang["does_not_exist"]		= "Не существует";
$pgv_lang["db_setup_bad"]		= "Конфигурация настоящей базы данных испорчена. Проверьте связующие параметры базы данных и установите их заново.";
$pgv_lang["db_setup_bad"]		= "Конфигурация существующе";
$pgv_lang["click_here_to_continue"]	= "Продолжить";
$pgv_lang["config_help"]		= "Объяснение конфигурации";
$pgv_lang["index"]			= "Индексные файлы";
$pgv_lang["admin_gedcoms"]		= "Кликните здесь для управления GEDCOMs";
$pgv_lang["current_gedcoms"]		= "Существующие GEDCOMs";
$pgv_lang["ged_download"]		= "Выгрузить";
$pgv_lang["admin_gedcoms"]		= "Кликните здесь для управления GEDCOM";
$pgv_lang["admin_gedcoms"]		= "Кликните здесь для управление GEDCOM";
$pgv_lang["ged_gedcom"]			= "Файл GEDCOM";
$pgv_lang["ged_title"]			= "Название GEDCOM";
$pgv_lang["ged_config"]			= "Установки файла";
$pgv_lang["show_phpinfo"]		= "Показать PHPInfo";
$pgv_lang["confirm_gedcom_delete"]	= "Вы уверены что хотите удалить этот GEDCOM файл?";
$pgv_lang["disabled"]			= "Исключить";
$pgv_lang["mouseover"]			= "Указателем мышки";
$pgv_lang["mousedown"]			= "Нажатием кнопки мышки";
$pgv_lang["click"]			= "Кликнув мышкой";
$pgv_lang["mailto"]	 		= "Написать эл.письмо (e-mail)";
$pgv_lang["messaging"]			= "Внутренние сообщения PhpGedView";
$pgv_lang["messaging2"]			= "Внутренние сообщения и эл.письма (e-mail)";
$pgv_lang["no_messaging"]		= "Нет контактов";
$pgv_lang["no_logs"]			= "Выключить LOG-файл";
$pgv_lang["daily"]			= "Ежедневно";
$pgv_lang["weekly"]			= "Еженедельно";
$pgv_lang["monthly"]			= "Ежемесячно";
$pgv_lang["yearly"]			= "Ежегодно<br />";
$pgv_lang["PGV_DATABASE"] 		= "База данных PhpGedView:";
$pgv_lang["PGV_DATABASE_help"] 		= "Здесь Вы устанавливаете какой тип сохраненных данных Вы хотите использовать для GEDCOM файлов, которые Вы импортируете. Отбирите  &quot;Indexbestanden&quot; для сохранения в индексной папке (Index-map), отбирите &quot;MySQL&quot; для использования в MySQL-базе данных.";
$pgv_lang["DBHOST"] 			= "Компьютер, где находится база данных MySQL";
$pgv_lang["DBHOST_help"] 		= "Имя DNS или IP адрес компьютера где находится база данных MySQL.";
$pgv_lang["DBUSER"] 			= "Имя пользователя даты базу MySQL:";
$pgv_lang["DBUSER_help"] 		= "Имя пользователя базы данных MySQL, которое необходимо для входа в базу данных.";
$pgv_lang["DBPASS"] 			= "Пароль доступа к базе данных MySQL";
$pgv_lang["DBPASS_help"] 		= "Пароль пользователя базой данных MySQL.";
$pgv_lang["DBNAME"] 			= "Имя базы данных:";
$pgv_lang["DBNAME_help"] 		= "В компьютере, где находится база данных MySQL, выбрать базу данных, которую будет использовать PhpGedView. Имя пользователя находится в соответствующем поле пользователя. Данный пользователь должен иметь следующие права на эту базу данных: создание, вставка, выгрузка, удаление и выбор. Это находится в переменной DBNAME файла config.php.<br />";
$pgv_lang["TBLPREFIX"]			= "Префиксы (титулы) таблиц базы данных:";
$pgv_lang["TBLPREFIX_help"]		= "Префикс (титул) для таблиц MySQL, созданных PhpGedView. Изменив это значение, Вы можете использовать несколько PhpGedView сайтов одной базы данных, но различные таблицы.";
$pgv_lang["DEFAULT_GEDCOM"]		= "Стандартный GEDCOM:";
$pgv_lang["DEFAULT_GEDCOM_help"]	= "MySQL версия PhpGedView содержит возможность помещения в одну базу данных нескольких GEDCOM файлов. Используйте эту переменную для установки стандартных GEDCOM файлов для всех пользователей. Для первоначально импортируемого GEDCOM используется пустое значение. Если пользоваатель имеет возможность изменения GEDCOM, то на следующей странице будет находится линк с возможностью корректировки GEDCOM.";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]	= "Разрешить пользователям вносить изменения в GEDCOMs:";
$pgv_lang["ALLOW_CHANGE_GEDCOM_help"]	= "Загрузить Ваш GEDCOM файл в месте, которое будет досягаемым PHP на Вашем сервере. Введите путь, где он располагается.";
$pgv_lang["GEDCOM"]			= "GEDCOM путь:";
$pgv_lang["gedcom_path_help"]		= "Загрузить сначала Ваш GEDCOM файл на локальный сервер, который достает PHP. Затем введите путь к файлу и имя файла. Более подробное объяснение смотри в файле <a href=\"readme.txt\">readme.txt</a>.";
$pgv_lang["CHARACTER_SET"]		= "Код типа шрифта:";
$pgv_lang["CHARACTER_SET_help"]		= "Это тип шрифта Вашего GEDCOM файла. UTF-8 - стандартный шрифт и он должен быть читаемым почти на всех сайтах. Если GEDCOM файл использует кодировку ibm-windows, тогда Вы должны здесь поставить WINDOWS.<br /><br />ПРИМЕЧАНИЕ: PHP не поддерживает UNICODE (UTF-16); Поэтому не выбирайте его во избежании жалоб со стороны поддержки PHP :-)";
$pgv_lang["LANGUAGE"]			= "Язык:";
$pgv_lang["LANGUAGE_help"]		= "Установить стандарный язык для этого сайта. Пользователи могут сделать эту установку используя браузер установок или используя формуляр в конце страницы в случае если ENABLE_MULTI_LANGUAGE = true.";
$pgv_lang["ENABLE_MULTI_LANGUAGE"]	= "Разрешить пользователю менять язык:";
$pgv_lang["ENABLE_MULTI_LANGUAGE_help"] = "Для того чтобы дать возможность пользователям выбирать язык из списка внизу экрана, поместите это в &quot;yes&quot; Стандартным является язык, установленный через браузер.";
$pgv_lang["CALENDAR_FORMAT"]		= "Формат календаря:";
$pgv_lang["CALENDAR_FORMAT_help"]	= "Здесь Вы определяете тип календаря, который Вы хотите использовать с этим GEDCOM файлом. Древнееврейский календарь (Иврит) является таким же, как еврейский, но с дневнееврейскими символами. Внимание: Еврейские/Дневнееврейские даты вычислены с помощью Грегорианских/Юлианских дат. Так как в Еврейском календаре отсчет начинается с захода солнца, события которые произошли между заходом солнца и полночью имеют дату на один день ранее чем день по дате Еврейского календаря. Также воспроизведение Иврита может быть проблемой для старых поисковых сайтов Интернета. Мы показываем Иврит задом наперед или не полностью.";
$pgv_lang["DISPLAY_JEWISH_THOUSANDS"]	= "Отобразить тысячи в Иврите:";
$pgv_lang["DISPLAY_JEWISH_THOUSANDS_help"]	= "Показать алафим в календарях Иврита. \"Да\" отображает год 1969 как <span lang=\"he-IL\" dir=\'rtl\'>&#1492;\'&#160;&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm; , \"Нет\" как <span lang=\"he-IL\" dir=\'rtl\'>&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm;. Это не влияет на значение Еврейского года, он остается 5729 несмотря на эту установку.";
$pgv_lang["DISPLAY_JEWISH_GERESHAYIM"]		= "Показать гэрэш в Иврите:";
$pgv_lang["DISPLAY_JEWISH_GERESHAYIM_help"]	= "Показать двойные и одиночные кавычки при отображении дат в Иврите. Это значение на \"Да\" показывает дату от 8 февраля 1969 года как <span lang=\'he-IL\' dir=\'rtl\'>&#1499;\'&#160;&#1513;&#1489;&#1496;&#160;&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm; это значение на \"Нет\" показывается как <span lang=\'he-IL\' dir=\'rtl\'>&#1499;&#160;&#1513;&#1489;&#1496;&#160;&#1514;&#1513;&#1499;&#1496;</span>&lrm;. Это не оказывает влияния на значение Еврейского года, т.к. кавычки в Еврейских датах отображаются шрифтом латинской кодировки.<br />Внимание: Это значение календаря на PHP 5.0 равняется постоянным CAL_JEWISH_ADD_ALAFIM_GERESH и CAL_JEWISH_ADD_GERESHAYIM. Они обе оказывают влияние на значение.";
$pgv_lang["JEWISH_ASHKENAZ_PRONUNCIATION"]	= "Еврейский Аскеназский словарь:";
$pgv_lang["JEWISH_ASHKENAZ_PRONUNCIATION_help"] = "Выбрать Еврейско-Ашкеназский словарь.<br />При &quot;JA&quot; месяцы Хешван и Тевес пишутся по ашкеназскому словарь. При &quot;NEE&quot; остаются без изменений.<br />Это работает только в еврейских установках. В случае установки Иврита используется алварит Иврита.";
$pgv_lang["JEWISH_ASHKENAZ_PRONUNCIATION_help"] = "Выбрать Еврейско-Ашкеназский словарь.<br />При &quot;JA&quot; месяцы Хешван и Тевес пишутся по ашкеназскому словарь. При &quot;NEE&quot; остаются без изменений.<br />Это работает только в еврейских установках. В случае установки Иврита используется алварит Иврита.";
$pgv_lang["DEFAULT_PEDIGREE_GENERATIONS"]	= "Восходящее дерево поколений:";
$pgv_lang["DEFAULT_PEDIGREE_GENERATIONS_help"]	= "Указать число поколений, сколько будет показано на стандартном изображении графика восходящего дерева.";
$pgv_lang["MAX_PEDIGREE_GENERATIONS"]		= "Максимальное число поколений по восходящему дереву:";
$pgv_lang["MAX_PEDIGREE_GENERATIONS_help"]	= "Установить максимальное число поколений, которое будет показываться в восходящем дереве.";
$pgv_lang["MAX_DESCENDANCY_GENERATIONS"]	= "Максимальное число поколений потомков:";
$pgv_lang["MAX_DESCENDANCY_GENERATIONS_help"]	= "Установить максимальное число поколений, которое будет показываться в графике потомков.";
$pgv_lang["USE_RIN"]			= "Использовать  RIN вместо GEDCOM-ID:";
$pgv_lang["USE_RIN_help"]		= "Выбери &quot;JA&quot; при использовании RIN-номеров вместо GEDCOM-ID. Это используется в файлах конфигурации, установках пользователей и обзорах. Это полезно для генеологических программ, которые не принадлежат к экспортируемым ID, а используют всегда одинаковый RIN.";
$pgv_lang["PEDIGREE_ROOT_ID"]		= "Стартовая персона для восходящего дерева и графиков потомков:";
$pgv_lang["PEDIGREE_ROOT_ID_help"]	= "Поместить ID номер стартовой персоны для восходящего дерева и графиков потомков.";
$pgv_lang["GEDCOM_ID_PREFIX"]		= "GEDCOM ID префикс:";
$pgv_lang["GEDCOM_ID_PREFIX_help"]	= "При воспроизведении восходящего дерева, потомков, родства и др., делается запрос пользователям ввести ID номер. Если здесь префикс не введен, то это добавляется.";
$pgv_lang["PEDIGREE_FULL_DETAILS"]	= "Показывать данные о рождении и кончине на карте предков:";
$pgv_lang["PEDIGREE_FULL_DETAILS_help"] = "Указать будут ли выдны данные о рождении и кончине персоны.";
$pgv_lang["PEDIGREE_LAYOUT"] 		= "Создать стандартное восходящее дерево:";
$pgv_lang["PEDIGREE_LAYOUT_help"] 	= "Задайте горизонтальное или вертикальное позицирование восходящего дерева.";
$pgv_lang["SHOW_EMPTY_BOXES"]		= "Показать пустые контейнеры в восходящем дереве:";
$pgv_lang["SHOW_EMPTY_BOXES_help"]	= "Выбирете здесь будут ли показаны пустые контейнеры в графике восходящего дерева.";
$pgv_lang["ZOOM_BOXES"]			= "Увеличить изображение:";
$pgv_lang["ZOOM_BOXES_help"]		= "Дать возможность пользователю увеличивать задачи, т.е. показывать больше информации. \"Убрать отметку (\"галочку\")\" отключает эту функцию, \"Указателем мышки\" активизирует функцию когда линия на экрана попадет на иконку, \"Щелнув мышкой\" активизирует функцию пока нажата кнопка мышки, \"Кликнув мышкой\" изменяет функцию - включить/отключить.";
$pgv_lang["LINK_ICONS_help"]		= "Дать возможность пользователю \"перепрыгивать\" на другие отображения и родства персоны. \"Убрать отметку (\"галочку\")\" отключает эту функцию, \"Указателем мышки\" активизирует функцию когда линия на экрана попадет на иконку, \"Щелнув мышкой\" активизирует функцию пока нажата кнопка мышки, \"Кликнув мышкой\" изменяет функцию - включить/отключить.";
$pgv_lang["PRIVACY_MODULE"]		= "Личный файл:";
$pgv_lang["ZOOM_BOXES"]			= "Увеличить изображение:";
$pgv_lang["PRIVACY_MODULE_help"]	= "Файл где находятся персональные функции. Для получения большей информации и для получения альтернативных персональных добавлений смотри<a href=\"http://gendorbendor.sourceforge.net\">http://gendorbendor.sourceforge.net</a>";
$pgv_lang["PRIVACY_MODULE_help"]	= "|Файл где находятся персональные функции. Для получения большей информации и для получения альтернативных персональных добаволений смотри<a href=\"http://gendorbendor.sourceforge.net\">http://gendorbendor.sourceforge.net</a>";
$pgv_lang["HIDE_LIVE_PEOPLE"]		= "Используйте личные установки:";
$pgv_lang["HIDE_LIVE_PEOPLE_help"]	= "В этой опции Вы можете отметить \"галочками\" личные модули и скрыть детальные данные о ныне живущих персонах. Ныне живущие персоны не имеют событий, которые произошли раньше чем число лет, определенное в переменной \$MAX_ALIVE_AGE.<br />В личном модуле Вы можете обрабатывать личные установки.";
$pgv_lang["REQUIRE_AUTHENTICATION"]	= "Требуется ли быть авторизированным пользователем:";
$pgv_lang["REQUIRE_AUTHENTICATION_help"] = "Отметив эту функцию задается что все посетители должны ввести свой логин прежде чем они смогут посмотреть данные на сайте.";
$pgv_lang["HIDE_LIVE_PEOPLE_help"]	= "В этой опции Вы можете отметить \"галочками\" личные модули и скрыть детальные данные о ныне живущих персонах. Ныне живущие персоны не имеют событий, которые произошли раньше чем число лет, определенное в переменной \$MAX_ALIVE_AGE.<br />В личном модуле Вы можете обрабатывать личные установки.<br />Это задается в переменно";
$pgv_lang["CHECK_CHILD_DATES"]		= "Проверьте даты детей:";
$pgv_lang["CHECK_CHILD_DATES_help"]	= "Проверьте даты детей чтобы определить является ли данной лицо ныне живущим. В старых версиях и большом GEDCOM файле это чувствительно замедляет работу сайта.";
$pgv_lang["MAX_ALIVE_AGE"]		= "Максимально предполагаемый возвраст персоны, на котором произошла его кончина.";
$pgv_lang["MAX_ALIVE_AGE_help"]		= "Максимальный возраст который могла иметь данная персона или мак.возвраст его/ее детей, чтобы определить является персона ныне живущей или нет.";
$pgv_lang["SHOW_GEDCOM_RECORD"]		= "Дать согласие на просмотр правил GEDCOM пользователям:";
$pgv_lang["ALLOW_EDIT_GEDCOM"]		= "Разрешить корректировать:";
$pgv_lang["INDEX_DIRECTORY"]		= "Директория для индексных файлов:";
$pgv_lang["INDEX_DIRECTORY_help"]	= "Путь к папке, где должны находиться индексные файлы  PhpGedView (включая \"/\" в конце).";
$pgv_lang["ALPHA_INDEX_LISTS"]		= "Подразделить длинные списки по первой букве:";
$pgv_lang["SHOW_ID_NUMBERS_help"]	= "В графиках показывать в скобках за именем ID-номер персоны.";
$pgv_lang["SHOW_PEDIGREE_PLACES"]	= "Показать географические места в контейнерах персон:";
$pgv_lang["SHOW_PEDIGREE_PLACES_help"]	= "Установите сколько уровней данных о рождении и кончине будет отображаться в графиках восходящего дерева и графиках поколений. При значении 9 - будут показаны все уровни, при значении 0 - ни одного. При значении 1 будет показан только первый уровень, при значении 2 - первый и второй и т.д.";
$pgv_lang["MULTI_MEDIA"]		= "Прикрепить возможности мультимедиа (фото/аудио/видео):";
$pgv_lang["MULTI_MEDIA_help"]		= "GEDCOM 5.5 содержит возможность присоединять картинки, видео и другие мультимедийные объекты в GEDCOM файл. Если в Вашем GEDCOM нет мультимеа-объектов то Вы можете отключить эту возможность установив в переменной \"Нет\".<br />Для подробной информации использования мультимедийных объектов смотреть параграф о мультимедиа <a href=\"readme.txt\">readme.txt</a>.";
$pgv_lang["MEDIA_DIRECTORY"]		= "Папки мультимедиа:";
$pgv_lang["MEDIA_DIRECTORY_help"]	= "Путь к директории, где PhpGedView собирает локальные медиа-данные (с последним \"/\".";
$pgv_lang["MEDIA_DIRECTORY_LEVELS"]	= "Количество уровней в мультимедиа-папках:";
$pgv_lang["MEDIA_DIRECTORY_LEVELS_help"]	= "При значении 0 все папки будут пропускаться по пути на медиа объект. При значении 1 будет использоваться также первая объекта. По мере увеличения значения увеличивается число папок для использования.<br />Например: если линк на объект стоит на C:\\Documents and Settings\\User\\My Documents\\My Pictures\\Genealogy\\Surname Line\\grandpa.jpg, то значение 0 переводится как путь ./media/grandpa.jpg. Значение 1 переводится как путь ./media/Surname Line/grandpa.jpg и т.д. Для большенство людей достаточно значение 0. Но бывает что некоторые медиа объекты с одинаковым именем перезаписывают друг друга. Это делает возможным установливать желаемую структуру медиа-папок и предупреждает случаи двойных имен.";
$pgv_lang["ENABLE_CLIPPINGS_CART"]	= "Прикрепить альбом:";
$pgv_lang["HIDE_GEDCOM_ERRORS"]		= "Скрыть ошибки GEDCOM:";
$pgv_lang["WORD_WRAPPED_NOTES"]		= "Ввести пробелы в случае прерванных замечаний";
$pgv_lang["SHOW_CONTEXT_HELP"]		= "Показывать \"?\" (линк помощника) на страницах";
$pgv_lang["HOME_SITE_URL"]		= "Web-сайт URL";
$pgv_lang["HOME_SITE_TEXT"]		= "Web-сайт текст";
$pgv_lang["CONTACT_EMAIL"]		= "Контактный e-mail по генеологическим данным:";
$pgv_lang["CONTACT_EMAIL_help"]		= "Эл.адрес (e-mail) для вопросов по данным этого сайти по генеологии.";
$pgv_lang["CONTACT_METHOD"] 		= "Способ общения:";
$pgv_lang["WEBMASTER_EMAIL"]		= "e-mail администратора сайта:";
$pgv_lang["WEBMASTER_EMAIL_help"]	= "Эл.адрес (e-mail) для обращения по техническим вопросам или об ошибках, найденных на сайте.";
$pgv_lang["SUPPORT_METHOD"] 		= "Способ поддержки:";
$pgv_lang["FAVICON"]			= "Фаворитные иконки:";
$pgv_lang["THEME_DIR"]			= "Папка тем:";
$pgv_lang["THEME_DIR_help"]		= "Папка куда PhpGedView сохраняет файлы тем. Вы можете каждую стандартную тему предоставляемую PhpGedView подладить так, чтобы она показывалась уникально. Для большей информации смотреть параграф подналадки тем в <a href=\"readme.txt\">readme.txt</a>.";
$pgv_lang["THEME_DIR_help"]		= "Папка куда PhpGedView сохраняет файлы тем. Вы можете каждую стандартную тему предоставляемую PhpGedView подладить так, чтобы она показывалась уникально. Для большей информации смотреть параграф подналадки тем в <a href=\"readme.txt\">readme.txt</a>.";
$pgv_lang["TIME_LIMIT"]			= "Лимитное время PHP:";
$pgv_lang["TIME_LIMIT_help"]		= "Максимальное время работы PhpGedView в секундах. Стандартное время - 1 минута. В зависимости от формата GEDCOM файла бывает необходимо увеличивать время для создания индексов. При значении 0 PHP будет работать неограниченно.<br />Внимание: При установлении значения 0 или другого большего числа возможно что веб-сайт будет висеть в определенной обработке пока работает сценарий. Может случиться что при значении 0 будет возможно остановить обработку только при перезагрузке компьютера администратором. Большие таблицы могут действительно долго обрабатываться. Чтобы кто-либо не смог остановить сервер при запросе листа 1000-го поколения, устанавливается как можно меньшее значение.";
$pgv_lang["PGV_SESSION_SAVE_PATH"]	= "Папка для файлов сессии:";
$pgv_lang["PGV_SESSION_SAVE_PATH_help"] = "Папку, куда PhpGedView будет сохранять файлы сессии. На некоторых компьютерах PHP установлен не хорошо и данные сессии не сохраняются во время смены экрана. Этой установкой определяется папка куда будут сохраняться файлы. /INDEX/ папка является хорошим выбором. Если это значение оставить пустым, то будет использоваться папка, опереденная в php.ini файле.";
$pgv_lang["SERVER_URL"] 		= "URL сервера:";
$pgv_lang["SERVER_URL_help"] 		= "Если вы используете не стандартый порт, то введите здесь URL на ваш сервер.";
$pgv_lang["PGV_SESSION_TIME"]		= "Время сессии истекло:";
$pgv_lang["PGV_SESSION_TIME_help"]	= "Время в секундах сколько PhpGedView страница остается активной до того, как будет необходимо еще раз ввести логин. По умолчанию это 120 минут.";
$pgv_lang["SHOW_STATS"]			= "Показать обработанные данные:";
$pgv_lang["SHOW_STATS_help"]		= "Показывать обрабатываемые данные и запросы базы данных внизу каждого экрана.";
$pgv_lang["download_here"]		= "Кликните здесь для выгрузки файла.";
$pgv_lang["download_gedconf"]		= "Загрузить конфигурацию GEDCOM.";
$pgv_lang["not_writable"]		= "PHP не имеет доступа к записи в Ваш файл конфигурации. Для того чтобы поместить файл куда-нибудь, кликните по загрузить_кнопку. Далее возможно вручную поместить файл на нужное место.";
$pgv_lang["upload_to_index"]		= "Выгрузить файл в индексную папку.";
$pgv_lang["edit_privacy"]		= "Конфигурация личного файла";
$pgv_lang["edit_privacy_title"]			= "Изменить личные установки GEDCOM";
$pgv_lang["PRIV_PUBLIC"]		= "Видимо для ВСЕХ посетителей сайта";
$pgv_lang["PRIV_USER"]			= "Видимо только для авторизированных пользователей";
$pgv_lang["PRIV_NONE"]			= "Видимо только для администаторов";
$pgv_lang["PRIV_HIDE"]				= "Скрыть даже для администраторов";
$pgv_lang["save_changed_settings"]	= "Сохранить изменения";
$pgv_lang["add_new_pp_setting"]		= "Добавить новые личные установки для персоны";
$pgv_lang["add_new_up_setting"]		= "Добавить новые личные установки пользователя";
$pgv_lang["add_new_gf_setting"]		= "Добавить новые общие установки для данных";
$pgv_lang["add_new_pf_setting"]		= "Добавить новые специфические установки для данных";
$pgv_lang["add_new_pf_setting_source"]		= "Добавить новые установки для данных источника";
$pgv_lang["privacy_source"]			= "Источник";
$pgv_lang["privacy_indi"]			= "Персона";
$pgv_lang["privacy_indi_source"]		= "Персона / Источник";
$pgv_lang["privacy_source_id"]			= "Источник - ID";
$pgv_lang["privacy_indi_id"]			= "Лица - ID";
$pgv_lang["add_new_pf_setting_indi"]		= "Добавить новую установку данных персоны";
$pgv_lang["file_read_error"]		= "ОШИБКА!!! Невозможно прочитать личный файл!";
$pgv_lang["general_settings"]		= "Общие персональные установки";
$pgv_lang["person_privacy_settings"]		= "Личные установки для персоны";
$pgv_lang["edit_exist_person_privacy_settings"]	= "Изменить существующие личные установки персоны";
$pgv_lang["user_privacy_settings"]		= "Личные установки для пользователя";
$pgv_lang["edit_exist_user_privacy_settings"]	= "Изменить существующие личные установки пользователя";
$pgv_lang["global_facts_settings"]		= "Общие установки для данных";
$pgv_lang["edit_exist_global_facts_settings"]	= "Изменить существующие общие установки для данных";
$pgv_lang["person_facts_settings"]		= "Специфичные установки для данных";
$pgv_lang["edit_exist_person_facts_settings"]	= "Изменить существующие специфические установки для данных персоны";
$pgv_lang["accessible_by"]			= "Видимый для кого?";
$pgv_lang["hide"]				= "Скрыть";
$pgv_lang["show_question"]			= "Показывать?";
$pgv_lang["user_name"]				= "Имя пользователя";
$pgv_lang["name_of_fact"]			= "Описание данных";
$pgv_lang["choice"]				= "Выбор";
$pgv_lang["fact_show"]				= "Показать данные";
$pgv_lang["fact_details"]			= "Показать подробные данные";
$pgv_lang["privacy_header"]			= "Изменить личные внутренние установки";
$pgv_lang["unable_to_find_privacy_indi"]	= "Персона с этим ID номером не найдена";
$pgv_lang["save_and_import"]			= "После того как новая конфигурация GEDCOM сохранена, Вы должны заново импортировать GEDCOM кликнув по кновку <b>Импортировать GEDCOM</b> или идти на <b>Администрирование->Администратор GEDCOMs->Импортировать GEDCOM файл</b>";
$pgv_lang["SHOW_LIVING_NAMES"]			= "Имена живущих ныне лиц видимы";
$pgv_lang["SHOW_RESEARCH_LOG"]			= "Архив Log-файлов видим";
$pgv_lang["USE_RELATIONSHIP_PRIVACY"]		= "Использовать личные отношении";
$pgv_lang["MAX_RELATION_PATH_LENGTH"]		= "Максим.длина ветви родства";
$pgv_lang["CHECK_MARRIAGE_RELATIONS"]		= "Проверить родство по браку";
$pgv_lang["SHOW_DEAD_PEOPLE"]			= "Показать умерших лиц";
$pgv_lang["help_info"]				= "По каждой теме Вы можете вызвать помощник кликнув по красной &quot;?&quot; рядом с ярлычком каждого поля.";
$pgv_lang["SHOW_LIVING_NAMES_help"]		= "Показать имена ныне живущих лиц<br /><br />Имена ныне живущих лиц должны быть видимы для ВСЕХ посетителей сайта.";
$pgv_lang["SHOW_RESEARCH_LOG_help"]		= "Видимость архива Log-файла<br /><br />Какие пользователи могут просмотреть архив Log-файла, в случае если это установлено.";
$pgv_lang["USE_RELATIONSHIP_PRIVACY_help"]	= "Использовать личное родство<br /><br />\"Нет\" обозначает что авторизированные пользователи могут смотреть детальные сведения обо всех живущих ныне персонах.<br />Значение \"Да\" обозначает что пользователи могут смотреть персональную информацию о ныне живущих персонах только в случае если они состоят в родстве с просматриваемой персоной.";
$pgv_lang["MAX_RELATION_PATH_LENGTH_help"]	= "Максимальная длина ветви родства<br /><br />Внучатые племянники и племянницы.";
$pgv_lang["CHECK_MARRIAGE_RELATIONS_help"]	= "Проверьте родство по браку<br /><br />Проверьте родство через брачные отношения.";
$pgv_lang["SHOW_DEAD_PEOPLE_help"]		= "Показать умерших лиц<br /><br />Установить персональные уровни доступа ко всем умершим персонам";
$pgv_lang["person_privacy_help"]		= "\"Личные установки пользователя\" дают администраторам возможность не делать стандарные личные установки для опеределенных персон из GEDCOM файла. Допустим например, что есть ребенок, который умер в младенчестве. По умолчанию детальные сведения о нем видимы для всех пользователей, т.к. он является умершим. Но сведения о других членах семьи являются личными. Вы не хотите удалять данные об умершем ребенке, но хотите подробности скрыть и сделать личными. Допустим ребенок имел ID номер I100, тогда Вы должны ввести следующие установки: <br /><br />ID: I100<br />Видимо для: \"Видимо только для авторизированных пользоателей\"<br /><br />Наоборот это работает также. Если я хочу детали о ком-то (ID 101) сделать окрытыми, и я знаю что лицо является умершим, но у меня нет даты смерти, я ввожу следующее:<br /><br />ID: I101<br />Видимость для: \"Видимо для ВСЕХ посетителей\".";
$pgv_lang["user_privacy_help"]			= "\"Личные установки пользователя\" дают администраторам возможность не делать стандарные персональные установки для персон из GEDCOM файла, а базироваться на имя пользователя.<br /><br />Так, если мой ID номер 100 и я не хочу чтобы пользователь с именем пользователя \"Джон\" видел мои детальные сведения, я делаю следующие установки:<br /><br />Имя пользователь: Джон<br />ID: I100<br />Видимость?: \"Скрыть\"<br /><br />и мои детальные данные будут скрыты только для пользователя с именем пользователя \"Джон\".<br /><br />Для того чтобы сделать детальные сведения персоны I101 видимыми для \"Джона\" (которые по умолчанию скрыты, т.к. I101 персона является ныне живущим) установите следующее:<br /><br />Имя пользователя: Джон<br />ID: I101<br />Видимость?: \"Показывать\".";
$pgv_lang["edit_langdiff"]		= "Обработка и конфигурирование языковых файлов";
$pgv_lang["edit_lang_utility"]		= "Программа обработки файла языка";
$pgv_lang["edit_lang_utility_help"]	= "Эта программа используется для ручного перевода или корректировки перевода файла.<br />Программма отображает список англоязычных файлов. Сначала из этого списка выбираете нужный файл.<br />Далее находите нужный текст на английском языке, кликаете по аналогичному тексту под ним и пишите или изменяете текст. Затем сохраняете изменения.";
$pgv_lang["language_to_edit"]		= "Язык для корректировки";
$pgv_lang["file_to_edit"]		= "Языковые файлы для корректировки";
$pgv_lang["USE_REGISTRATION_MODULE"] 	= "Ввод пользователей посредством регистрации имен пользователей:";
$pgv_lang["USE_REGISTRATION_MODULE_help"] = "Дать возможность пользователям регистрировать себя на сайте. Она становится активной после одобрения администратора.";
$pgv_lang["ALLOW_USER_THEMES"] 		= "Позволить пользователям выбирать свое личное оформление сайта:";
$pgv_lang["ALLOW_USER_THEMES_help"] 	= "Дать пользователям возможность выбирать свое собственное оформление темы.";
$pgv_lang["gedcom_title_help"] 		= "Введите название, описывающее содерждание этого GEDCOM файла.";
$pgv_lang["LOGFILE_CREATE"] 		= "Архивировать Log-файлы:";
$pgv_lang["LOGFILE_CREATE"] 		= "Акхивировать Log-файлы:";
$pgv_lang["LOGFILE_CREATE_help"] 	= "Задайте как часто программа должна архивировать Log-файлы.";
$pgv_lang["welcome_help"]		= "Этот проводник-помощник будет помогать в процессе конфигурации. Вы должны заполнить различные поля. Проводник будет обеспечивать Вас информацией о заполняемых полях. Вы можете закрыть проводник или снова открыть его щелкнув по \"?\" рядом с заполняемым полем.";
$pgv_lang["review_readme"] 		= "До того как идти далее с установками PhpGedView, Вы должны прочитать файл <a href=\"readme.txt\" target=\"_blank\">readme.txt</a>.<br /><br />Вы можете всегда вернуться назад на страницу установок. Найдите через браузер файл configure.php и откройте его.<br /><br />В каждом разделе Вы можете обратиться к помощнику нажав &quot;?&quot;";
$pgv_lang["save_config"] 		= "Сохранить установки";
$pgv_lang["lang_save"]			= "Сохранить";
$pgv_lang["contents"]			= "Содержание";
$pgv_lang["listing"]			= "Список";
$pgv_lang["no_content"]			= "Нет содержимого";
$pgv_lang["editlang_help"]		= "Изменить сообщение в файле языка";
$pgv_lang["savelang_help"]		= "Сохранить сообщение";
$pgv_lang["original_message"]		= "Оригинал текста";
$pgv_lang["message_to_edit"]		= "Изменить сообщение";
$pgv_lang["changed_message"]		= "|Измененное содержание|";
$pgv_lang["message_empty_warning"] 	= "ВНИМАНИЕ! Текст сообщения отсутствует в  [#LANGUAGE_FILE#]";
$pgv_lang["message_empty_warning"] 	= "ВНИМАНИЕ! Текст сообщения отсутствует в  [#LANGUAGE_FILE#].";
$pgv_lang["language_to_export"]		= "Выбери язык для экспорта";
$pgv_lang["export_lang_utility"]	= "Помощник экспорта языковых файлов";
$pgv_lang["export"]			= "Экспорт";
$pgv_lang["export_lang_utility_help"]	= "Выбирите язык помощника из выбранного языкового файла конфигурация_помощник для экспорта в файл документации. ";
$pgv_lang["export_ok"]			= "Экспорт информации-помощника завершен.";
$pgv_lang["compare_lang_utility"]	= "Помощник сравнивания языковых файлов";
$pgv_lang["compare_lang_utility_help"]	= "Этот модуль сравнивает два файла языка и создает список различий.<br /><br />В файле [<a href=\"languages\/LANG_CHANGELOG.txt\" target=\"_blank\">LANG_CHANGELOG.txt</a>] в папке languages указаны также другие изменения.";
$pgv_lang["new_language"]		= "Новый язык";
$pgv_lang["old_language"]		= "Старый язык";
$pgv_lang["compare"]			= "Сравнить";
$pgv_lang["comparing"]			= "Сравнить языковые файлы";
$pgv_lang["additions"]			= "Добавления";
$pgv_lang["no_additions"]		= "Нет изменений";
$pgv_lang["subtractions"]		= "Изменения";
$pgv_lang["no_subtractions"]		= "Нет изменений";
$pgv_lang["config_lang_utility"]	= "Использовавшийся язык конфигуратора";
$pgv_lang["config_lang_utility_help"]	= "С помощью этого модуля Вы можете просто подключить или отключить (поставив или убрав \"галочки\") поддерживаемые языки в PhpGedView. Кроме этого Вы можете также наладить специфичные установки страны и языка.";
$pgv_lang["active"]			= "Активный";
$pgv_lang["active_help"]		= "Разрешить пользователям выбирать язык в случае если разрешено выбирать другой язык.";
$pgv_lang["edit_settings"]		= "Изменить установки";
$pgv_lang["lang_edit"]			= "Корректировать";
$pgv_lang["lang_edit"]			= "|Корректировать|";
$pgv_lang["lang_language"]		= "Язык";
$pgv_lang["export_filename"]		= "Имя файла с экспортными данными: ";
$pgv_lang["lang_back"]			= "Назад на главное меню корректировки файлов языков и конфигурирования";
$pgv_lang["lang_back_admin"]		= "Назад на меню администратора";
$pgv_lang["lang_name_danish"]		= "Датский";
$pgv_lang["lang_name_german"]		= "Немецкий";
$pgv_lang["lang_name_english"]		= "Английский";
$pgv_lang["lang_name_spanish"]		= "Испанский";
$pgv_lang["lang_name_spanish-ar"]	= "Латино-Американский Испанский";
$pgv_lang["lang_name_portuguese-br"]	= "Бразильский - Португальский";
$pgv_lang["lang_name_portuguese"]	= "Португальский";
$pgv_lang["lang_name_french"]		= "Французский";
$pgv_lang["lang_name_italian"]		= "Итальянский";
$pgv_lang["lang_name_dutch"]		= "Недерланский";
$pgv_lang["lang_name_norwegian"]	= "Норвежский";
$pgv_lang["lang_name_polish"]		= "Польский";
$pgv_lang["lang_name_swedish"]		= "Швецкий";
$pgv_lang["lang_name_turkish"]		= "Турецкий";
$pgv_lang["lang_name_chinese"]		= "Китайский";
$pgv_lang["lang_name_hebrew"]		= "Иврит";
$pgv_lang["lang_name_russian"]		= "Русский";
$pgv_lang["original_lang_name"]		= "Оригинальное название языка в  #D_LANGNAME#";
$pgv_lang["original_lang_name_help"]	= "Здесь задается название языка в тот самом языке.";
$pgv_lang["original_lang_name_help"]	= "Здесь задается название языка в том самом языке.";
$pgv_lang["lang_shortcut"]		= "Сокращения языка";
$pgv_lang["lang_shortcut_help"]		= "Этой опцией Вы устанавливаете сокращение названия языка двумя буквами.";
$pgv_lang["lang_filename"]		= "Файл языка";
$pgv_lang["lang_filename_help"]		= "Имя и путь к файлу с переводом для программы.";
$pgv_lang["config_filename"]		= "Файл конфигурации и файл-помощник:";
$pgv_lang["config_filename_help"]	= "Имя и путь к файлу с переводом файлов конфигурации и помощников.";
$pgv_lang["facts_filename"]		= "Файл-факты:";
$pgv_lang["lang_filename_help"]		= "Имя и путь к файлу с переводом программы.";
$pgv_lang["facts_filename_help"]	= "Имя и путь к файлу с переводом GEDCOM-фактов.";
$pgv_lang["help_filename"]		= "Файл-помощник";
$pgv_lang["help_filename_help"]	= "Имя и путь к файлу помощника.";
$pgv_lang["flagsfile"]			= "Файл-флаг:";
$pgv_lang["flagsfile_help"]		= "Имя и путь к файлу с изображением флага выбранного языка.";
$pgv_lang["flagsfile"]			= "Файл-флаги:";
$pgv_lang["flagsfile"]			= "Файл - флаги:";
$pgv_lang["text_direction"]		= "Направление текста:";
$pgv_lang["text_direction_help"]	= "Здесь вы устанавливаете каким образом отображать тексты.";
$pgv_lang["date_format"]		= "Формат даты:";
$pgv_lang["date_format_help"]		= "В различных странах общеприняты различные форматы даты. В Недерландах это \"ДеньМесяцГод\", в тоже время как в Англии это часто \"МесяцДеньГод\". Здесь Вы устанавливаете как PhpGedView будет показывать дату.";
$pgv_lang["week_start"]			= "Неделя начинается с:";
$pgv_lang["week_start_help"]		= "День недели, с которой начинается неделя. В большенстве стран неделя начинается в воскресение, но в некоторых странах в понедельник или в другой день недели.";
$pgv_lang["name_reverse"]		= "Сначала фамилия:";
$pgv_lang["name_reverse_help"]		= "В некоторых языках сначала называется имя, затем фамилия. В нашей программе также такая комбинация является стандартной. Но в некоторых  языках используется обратная комбинация. Этой опцией Вы устанавливаете что на первом месте стоит фамилия, а затем имя.";
$pgv_lang["ltr"]			= "слева направо";
$pgv_lang["rtl"]			= "справа налево";
$pgv_lang["file_does_not_exist"]	= "Ошибка! Файл не существует!";
$pgv_lang["alphabet_upper"]		= "Алфавит (заглавные буквы)";
$pgv_lang["alphabet_upper_help"]	= "Алфавит в заглавных (прописных) буквах. Этот алфавит используется для сортировки имен, написанных заглавными буквами в списке персон в PhpGedView.";
$pgv_lang["alphabet_lower"]		= "Алфавит (строчные буквы)";
$pgv_lang["alphabet_lower_help"]	= "Алфавит в строчных буквах. Этот алфавит используется для сортировки имен, написанных строчными буквами в списке персон в PhpGedView.";
$pgv_lang["lang_config_write_error"]	= "Ошибка перезаписи из установок языка в файл [language_settings.php]. Проверьте права за запись и попробуйте заново.";
$pgv_lang["translation_forum_help"]	= "Этот линк открывает новое окно в Вашем Windows Explorer на форум переводчиков PhpGedView (http://sourceforge.net/forum/forum.php?forum_id=294245). Здесь вы можете задать вопросы по переводу, предложить темы для обсуждения и (если хотите) вложить свой вклад в перевод.";
$pgv_lang["translation_forum"]		= "На форум переводчиков на сайте PhpGedView SourceForge";
$pgv_lang["lang_save_success"]		= "Изменения в #PGV_LANG# записаны успешно";
$pgv_lang["click_here_to_continue"]	= "Кликните сюда для продолжения.";


?>
