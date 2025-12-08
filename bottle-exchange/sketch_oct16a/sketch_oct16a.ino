// กำหนดขา LED
const int ledPin = 2;

void setup() {
  // ตั้งขา LED เป็นขาออก
  pinMode(ledPin, OUTPUT);
}

void loop() {
  digitalWrite(ledPin, HIGH);  // เปิด LED
  delay(1000);                  // รอ 1 วินาที
  digitalWrite(ledPin, LOW);   // ปิด LED
  delay(1000);                  // รอ 1 วินาที
}
