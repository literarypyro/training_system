<?php


?>

<script>
function EvalSound(soundobj) {
  var thissound=document.getElementById(soundobj);
  thissound.Play();
}
</script>
<embed src="doorbell.wav" autostart=false width=0 height=0 id="sound1"
enablejavascript="true">
<form>
<input type="button" value="Play Sound" onClick="EvalSound('sound1')">
</form>
