(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.apply-the-job').on('click', function (e) {
      e.preventDefault();

      const button = $(this),
        jobId = button.data('job');

      jQuery.ajax({
        url: dcjb.rest.apply_job,
        type: 'POST',
        data: {
          job_id: jobId,
        },
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-WP-Nonce', dcjb.rest.nonce);
          button
            .find('.fa-spin')
            .removeClass('d-none')
            .parent()
            .find('span')
            .text('Applying...');
        },
        success: function (response) {
          if (response.success) {
            button
              .find('.fa-spin')
              .addClass('d-none')
              .parent()
              .attr('disabled', true)
              .removeClass('btn-primary apply-the-job')
              .addClass('btn-warning')
              .find('span')
              .text('Applied!');
          }
        },
      });
    });
  });
})(jQuery);
