<?php
global $post;
?>
<button type="button" class="btn btn-primary apply-the-job" data-job='<?= $post->ID; ?>'>
  <i class="fa fa-spinner fa-spin d-none" aria-hidden="true"></i>
  <span>Apply to the job</span>
</button>