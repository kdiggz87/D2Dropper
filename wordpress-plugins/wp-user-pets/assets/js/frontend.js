jQuery(document).ready(function($) {
    'use strict';
    
    var petImageUploader;
    
    // Upload pet image
    $('.wp-user-pets-form').on('click', '.upload-pet-image', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var imageInput = $('#pet_image_url');
        var preview = $('.pet-image-preview');
        
        if (petImageUploader) {
            petImageUploader.open();
            return;
        }
        
        petImageUploader = wp.media({
            title: 'Select Pet Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        petImageUploader.on('select', function() {
            var attachment = petImageUploader.state().get('selection').first().toJSON();
            imageInput.val(attachment.url);
            preview.html('<img src="' + attachment.url + '" style="max-width: 200px; margin-top: 10px;">');
        });
        
        petImageUploader.open();
    });
    
    // Add pet form submission
    $('#add-pet-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        var messageDiv = form.find('.form-message');
        
        messageDiv.removeClass('success error').text('');
        
        $.ajax({
            url: wpUserPets.ajaxUrl,
            type: 'POST',
            data: formData + '&action=wp_user_pets_add&nonce=' + wpUserPets.nonce,
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true).text('Adding...');
            },
            success: function(response) {
                if (response.success) {
                    messageDiv.addClass('success').text(response.data.message);
                    form[0].reset();
                    $('.pet-image-preview').empty();
                    // Reload the page to show the new pet
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    messageDiv.addClass('error').text(response.data.message);
                }
            },
            error: function() {
                messageDiv.addClass('error').text('An error occurred. Please try again.');
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false).text('Add Pet');
            }
        });
    });
    
    // Delete pet
    $('.wp-user-pets-list').on('click', '.delete-pet', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to delete this pet?')) {
            return;
        }
        
        var button = $(this);
        var petId = button.data('pet-id');
        var petCard = button.closest('.pet-card');
        
        $.ajax({
            url: wpUserPets.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wp_user_pets_delete',
                nonce: wpUserPets.nonce,
                pet_id: petId
            },
            beforeSend: function() {
                button.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    petCard.fadeOut(300, function() {
                        $(this).remove();
                        // Check if there are no more pets
                        if ($('.pets-grid .pet-card').length === 0) {
                            location.reload();
                        }
                    });
                } else {
                    alert(response.data.message);
                    button.prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                button.prop('disabled', false);
            }
        });
    });
});
