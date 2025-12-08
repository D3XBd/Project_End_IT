function signup() {
  const studentId = document.getElementById('studentId').value;
  const fullname = document.getElementById('fullname').value;
  const grade = document.getElementById('grade').value;
  const major = document.getElementById('major').value;
  const password = document.getElementById('password').value;

  if(!studentId || !fullname || !grade || !major || !password){
    alert("กรุณากรอกข้อมูลให้ครบทุกช่อง");
    return;
  }

  const email = studentId + "@school.com";

  // ====== ตรวจสอบว่ารหัสนักศึกษาเคยใช้แล้วหรือยัง ======
  auth.fetchSignInMethodsForEmail(email)
    .then(methods => {
      if(methods.length > 0){
        alert("รหัสนักศึกษานี้ได้สมัครแล้ว");
      } else {
        // ====== สมัครสมาชิก ======
        auth.createUserWithEmailAndPassword(email, password)
          .then((userCredential) => {
            const user = userCredential.user;

            db.collection("users").doc(user.uid).set({
              studentId,
              fullname,
              grade,
              major
            }).then(() => {
              alert("สมัครสมาชิกสำเร็จ!");
              window.location.href = "login.html";
            });
          })
          .catch((error) => {
            console.error("Signup Error:", error);
            alert("เกิดข้อผิดพลาด: " + error.message);
          });
      }
    })
    .catch(err => {
      console.error("Error checking studentId:", err);
      alert("เกิดข้อผิดพลาดระหว่างตรวจสอบรหัสนักศึกษา");
    });
}