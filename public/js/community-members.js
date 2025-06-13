document.addEventListener('DOMContentLoaded', function() {
    let memberToRemove = null;
    const removeMemberModal = new bootstrap.Modal(document.getElementById('removeMemberModal'));
    
    document.querySelectorAll('.remove-member-btn').forEach(button => {
        button.addEventListener('click', function() {
            memberToRemove = {
                id: this.dataset.memberId,
                name: this.dataset.memberName
            };
            
            document.getElementById('memberNameToRemove').textContent = memberToRemove.name;
            removeMemberModal.show();
        });
    });

    document.getElementById('confirmRemoveMember').addEventListener('click', function() {
        if (!memberToRemove) return;
        
        const button = this;
        const originalText = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Removiendo...';
        button.disabled = true;
        
        const pathParts = window.location.pathname.split('/');
        const communityId = pathParts[pathParts.indexOf('communities') + 1];
        
        fetch(`/communities/${communityId}/members/${memberToRemove.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {

                removeMemberModal.hide();

                showAlert('success', data.success);
                
                if (typeof(Storage) !== "undefined") {
                    Object.keys(localStorage).forEach(key => {
                        if (key.includes('community_search') || key.includes('search_results')) {
                            localStorage.removeItem(key);
                        }
                    });
                }
                
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.error || 'Error al remover miembro');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', error.message || 'Error al remover miembro');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    });
    
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `;
        
        const container = document.querySelector('.members-page .container-fluid > .row > .col-12');
        const header = container.querySelector('.members-header');
        header.insertAdjacentHTML('afterend', alertHtml);

        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
});
