<?php

$banned = mysql_query("SELECT reason, end FROM " . TB_PREFIX . "banlist WHERE active = 1 and uid = '" . $session->uid . "';");
$ban = mysql_fetch_assoc($banned);

echo "<h2>You Are Banned</h2><br /><br />";
echo "Sorry, your due to not obeying the rule and and use of <b>" . $ban['reason'] . "</b>,<br /> till <b>" . date("d/m/Y", $ban['end']) . " </b> Time <b>" . date("G:i:s", $ban['end']) . "</b> is banned.<br /><br /><br /><br />";
?>
<form action="logout.php?del_cookie" method="post">
<center>
<button type="submit" value="Login" name="s1" id="btn_login" onclick="document.snd.w.value=screen.width+':'+screen.height;">
        <div class="button-container"><div class="button-position"><div class="btl"><div class="btr"><div class="btc"></div></div></div><div class="bml"><div class="bmr"><div class="bmc"></div></div></div><div class="bbl"><div class="bbr"><div class="bbc"></div></div></div></div>
        <div class="button-contents">Continue</div></div>
        </button></center></form>