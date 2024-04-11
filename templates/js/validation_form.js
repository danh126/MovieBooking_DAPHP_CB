function checkInput(input) {

    // if (input.type === 'radio' && !input.checked) {
    //     const errorSpan = document.getElementById('error' + input.id.slice(-1));
    //     errorSpan.textContent = 'Please select an option.';
    //     return false;
    // }
    // Kiểm tra input null
    const inputValue = input.value.trim();
    const errorSpan = document.getElementById('error' + input.id.slice(-1));

    if (inputValue === '') {
        errorSpan.textContent = 'Vui lòng nhập thông tin!';
        return false;
    } else {
        errorSpan.textContent = '';
        return true;
    }
}
