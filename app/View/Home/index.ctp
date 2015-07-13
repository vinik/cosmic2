<div class="container">

  <div class="row centered">
    <div id="loader" class="pull-right">
      <?php echo $this->Html->image('ajax-loader.gif', array('alt' => 'loading...', 'border' => '0')); ?>
    </div>
  </div>

	<div class="row">


		<div>
      <table class="table table-striped" id="logTable">
          <tr>
            <td>Loading...</td>
          </tr>

      </table>

		</div>
		<div class="col-lg-3"></div>
	</div><!-- /row -->

</div><!-- /container -->

<script type="text/javaScript">
  $(document).ready(function(){

    function loaderOn() {
      $("#loader").removeClass("invisible");
    }

    function loaderOff() {
      $("#loader").addClass("invisible");
    }

    function getLogs() {
      loaderOn();
      $.ajax({
        url: 'Home/latest',
        dataTye: 'html',
        success: function(response){
          $("#logTable").html(response);
          loaderOff();
        }
      });
    }

    loaderOn();
    setInterval(getLogs, 3000);
  });
</script>
