/**
 * Choose90 CRM Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Mark email as read
        $('.mark-read').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var emailId = $button.data('email-id');
            
            $.ajax({
                url: choose90Crm.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'choose90_crm_mark_read',
                    email_id: emailId,
                    nonce: choose90Crm.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $button.closest('tr').find('.status-badge')
                            .removeClass('status-unread')
                            .addClass('status-read')
                            .text('Read');
                        $button.closest('tr').attr('data-status', 'read');
                    }
                }
            });
        });
        
        // Mark email as replied
        $('.mark-replied').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var emailId = $button.data('email-id');
            
            $.ajax({
                url: choose90Crm.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'choose90_crm_mark_replied',
                    email_id: emailId,
                    nonce: choose90Crm.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $button.closest('tr').find('.status-badge')
                            .removeClass('status-unread status-read')
                            .addClass('status-replied')
                            .text('Replied');
                        $button.closest('tr').attr('data-status', 'replied');
                    }
                }
            });
        });
        
        // Filter emails
        $('#crm-status-filter, #crm-chapter-filter').on('change', function() {
            filterEmails();
        });
        
        $('#crm-search').on('keyup', function() {
            filterEmails();
        });
        
        function filterEmails() {
            var status = $('#crm-status-filter').val();
            var chapter = $('#crm-chapter-filter').val();
            var search = $('#crm-search').val().toLowerCase();
            
            $('.crm-emails-list tbody tr').each(function() {
                var $row = $(this);
                var rowStatus = $row.attr('data-status');
                var rowChapter = $row.attr('data-chapter');
                var rowText = $row.text().toLowerCase();
                
                var show = true;
                
                if (status && rowStatus !== status) {
                    show = false;
                }
                
                if (chapter && rowChapter !== chapter) {
                    show = false;
                }
                
                if (search && rowText.indexOf(search) === -1) {
                    show = false;
                }
                
                $row.toggle(show);
            });
        }
        
        // Manage distribution list members
        $('.manage-members').on('click', function(e) {
            e.preventDefault();
            var listId = $(this).data('list-id');
            $('#members-' + listId).toggle();
        });
        
        // Add member to distribution list
        $('.add-member').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var listId = $button.data('list-id');
            var userId = $('#user-select-' + listId).val();
            
            if (!userId) {
                alert('Please select a user');
                return;
            }
            
            $.ajax({
                url: choose90Crm.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'choose90_crm_add_list_member',
                    list_id: listId,
                    user_id: userId,
                    nonce: choose90Crm.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                }
            });
        });
        
        // Remove member from distribution list
        $('.remove-member').on('click', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to remove this member?')) {
                return;
            }
            
            var $link = $(this);
            var listId = $link.data('list-id');
            var userId = $link.data('user-id');
            
            $.ajax({
                url: choose90Crm.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'choose90_crm_remove_list_member',
                    list_id: listId,
                    user_id: userId,
                    nonce: choose90Crm.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                }
            });
        });
        
        // Delete distribution list
        $('.delete-list').on('click', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this distribution list? This action cannot be undone.')) {
                return;
            }
            
            var $link = $(this);
            var listId = $link.data('list-id');
            
            $.ajax({
                url: choose90Crm.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'choose90_crm_delete_list',
                    list_id: listId,
                    nonce: choose90Crm.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                }
            });
        });
        
        // Show/hide IMAP settings based on capture method
        function toggleImapSettings() {
            var method = $('#choose90_crm_email_capture_method').val();
            if (method === 'imap') {
                $('.imap-settings').show();
            } else {
                $('.imap-settings').hide();
            }
        }
        
        $('#choose90_crm_email_capture_method').on('change', toggleImapSettings);
        toggleImapSettings();
    });
    
})(jQuery);

