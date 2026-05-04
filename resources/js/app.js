import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * DynamicFormController
 */
const initDynamicFields = () => {
    const typeSelect = document.querySelector('select[name="type"]');

    if (!typeSelect) {
        console.warn('DynamicForm: Поле select[name="type"] не найдено.');

        return;
    }

    const filterFields = () => {
        const selectedValue = typeSelect.value.toLowerCase().trim();
        const allFields = document.querySelectorAll('input[name], textarea[name], select[name]');

        allFields.forEach(field => {
            if (field === typeSelect) return;

            const fieldName = field.getAttribute('name').toLowerCase();
            const shouldShow = selectedValue && fieldName.includes(selectedValue);

            if (shouldShow) {
                field.classList.remove('hidden');
                revealLabel(field);
            } else {
                field.classList.add('hidden');
                hideLabel(field);
            }
        });
    };

    const revealLabel = (input) => {
        if (!input.id) return;
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label) label.classList.remove('hidden');
    };

    const hideLabel = (input) => {
        if (!input.id) return;
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label) label.classList.add('hidden');
    };

    typeSelect.addEventListener('change', filterFields);

    filterFields();
};

document.addEventListener('DOMContentLoaded', initDynamicFields);
