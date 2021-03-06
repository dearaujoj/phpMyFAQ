<?php
/**
 * AJAX: verifyurl
 *
 * Usage:
 *   index.php?uin=<uin>&action=ajax&ajax=verifyURL&id=<id>&artlang=<lang>
 *
 * Performs link verification when entries are shown in record.show.php
 *
 * PHP Version 5.2
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 *
 * The Initial Developer of the Original Code is released for external use
 * with permission from NetJapan, Inc. IT Administration Group.
 *
 * @category  phpMyFAQ
 * @package   Administration
 * @author    Minoru TODA <todam@netjapan.co.jp>
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2005-2012 NetJapan, Inc. and phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/2.0/ Mozilla Public License Version 2.0
 * @link      http://www.phpmyfaq.de
 * @since     2005-09-30
 */

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

header("Expires: Thu, 7 Apr 1977 14:47:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html");
header("Vary: Negotiate,Accept");
header("Content-type: text/xml; charset=utf-8");

$linkverifier = new PMF_Linkverifier($faqConfig, $user->getLogin());
if ($linkverifier->isReady() == false) {
    if (count(ob_list_handlers()) > 0) {
        ob_clean();
    }
    print "disabled";
    exit();
}

$linkverifier->loadConfigurationFromDB();

$id   = PMF_Filter::filterInput(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$lang = PMF_Filter::filterInput(INPUT_GET, 'lang', FILTER_SANITIZE_STRING);

if (!(isset($id) && isset($lang))) {
    //header("X-DenyReason: id/lang bad");
    header("HTTP/1.0 401 Unauthorized");
    header("Status: 401 Unauthorized");
    exit();
}

$faq->faqRecord = null;
$faq->getRecord($id);

if (!isset($faq->faqRecord['content'])) {
    header("HTTP/1.0 401 Unauthorized");
    header("Status: 401 Unauthorized");
    exit();
}

if (count(ob_list_handlers()) > 0) {
    ob_clean();
}

$linkverifier->parse_string($faq->faqRecord['content']);
$linkverifier->VerifyURLs($faqConfig->get('main.referenceURL'));
$linkverifier->markEntry($id, $lang);
print $linkverifier->getLinkStateString();
exit();
