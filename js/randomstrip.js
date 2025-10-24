        $(document).ready(function() {
            // Generate a random date when the button is clicked.
            $('#generate').on('click', function() {
                var include_used = $('#include_used').is(':checked') ? 1 : 0;
                $.ajax({
                    type: 'POST',
                    url: 'random_strip.php',
                    data: { include_used: include_used },
                    dataType: 'json',
                    success: function(response) {
                        let date_text = '<h1>' + response.date + '</h1>';
                        if (response.reason) {
                            $('.submitRow').hide();
                            date_text += '<br><h2>' + response.reason + '</h2>';
                        } else {
                            $('.submitRow').show();
                            $('#store_date').val(response.date);
                        }
                        if (response.fun_fact) {
                            date_text += '<br><h3>Fun Fact: ';
                            date_text += '<span class="normalText">';
                            date_text += response.fun_fact + '</span></h3>';
                        }
                        $('#the_date').html(date_text);},
                    error: function() {
                        alert('Error generating random date.');
                    }
                });
            });

            $('#date_store').on('click', function() {
                // Make an AJAX post call to record the strip, and handle
                // the JSON response.
                var date = $('#the_date').text();

                $.ajax({
                    type: 'POST',
                    url: 'record_strip.php',
                    data: { store_date: date },
                    success: function(response) {
                        $('#success_modal').modal('show');
                    },
                    error: function() {
                        $('#error_modal').modal('show');
                    }
                });
            });

            $('#yip').on('click', function() {
                $('#success_modal').modal('hide');
                location.reload();
            });

            $('#augh').on('click', function() {
                $('#error_modal').modal('hide');
            });
        });
