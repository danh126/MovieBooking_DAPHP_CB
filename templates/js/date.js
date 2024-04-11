// Lấy ngày hiện hành
var currentDate = new Date();

// Định dạng ngày hiện hành thành yyyy-MM-dd
var formattedDate = currentDate.toISOString().substr(0, 10);

// Gán giá trị cho input date
document.getElementById("dateInput").value = formattedDate;