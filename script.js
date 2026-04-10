/**
 * Antigravity Survey Logic - SECURE VERSION
 * Communicates with a backend proxy to protect API tokens and sensitive data.
 */

document.addEventListener('DOMContentLoaded', () => {
    const surveyForm = document.getElementById('survey-form');
    const successOverlay = document.getElementById('success-overlay');
    const header = document.querySelector('header');
    const submitBtn = document.getElementById('submit-btn');

    if (surveyForm) {
        surveyForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Collect data from Form
            const formData = new FormData(surveyForm);
            const payload = Object.fromEntries(formData.entries());

            // UI Feedback
            submitBtn.textContent = 'Procesando con seguridad...';
            submitBtn.disabled = true;

            try {
                // We send the data to OUR OWN backend proxy, not Airtable directly.
                // This keeps our API Tokens hidden from the browser inspection.
                const response = await fetch('process_survey.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error en el servidor');
                }

                // Success transition
                surveyForm.style.display = 'none';
                header.style.display = 'none';
                successOverlay.style.display = 'block';
                
                requestAnimationFrame(() => {
                    successOverlay.style.transition = 'all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    successOverlay.style.opacity = '1';
                    successOverlay.style.transform = 'scale(1)';
                });

            } catch (error) {
                console.error('Security/Network Error:', error);
                alert('No se pudo completar el envío de forma segura: ' + error.message);
                submitBtn.textContent = 'Enviar Encuesta';
                submitBtn.disabled = false;
            }
        });
    }
});
