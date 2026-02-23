#include <WiFi.h>
#include <HTTPClient.h>

// ================== WIFI ==================
const char* ssid = "D3x";
const char* password = "11113333";

// ================== SERVER ==================
const char* CHECK_STATUS_URL = "http://172.20.10.13/bottle-exchange/Proj/checkStatus.php";
const char* UPDATE_URL       = "http://172.20.10.13/bottle-exchange/Proj/updateBottle.php";

// ================== SENSOR ==================
#define TRIG_PIN 5
#define ECHO_PIN 18
#define BOTTLE_DISTANCE_CM 15.0

#define FORCE_PASS_MODE true   // <<< เปลี่ยนเป็น true ถ้าจะเอาผ่าน

bool exchangeMode = false;
unsigned long lastSend = 0;

// ================== SETUP ==================
void setup() {
  Serial.begin(115200);

  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  WiFi.begin(ssid, password);
  Serial.print("WiFi Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connected");
}

// ================== LOOP ==================
void loop() {
  checkStatus();

  if (exchangeMode) {
    if (FORCE_PASS_MODE) {
      forceSendBottle();
    } else {
      handleBottleDetection();
    }
  }

  delay(500);
}

// ================== FUNCTIONS ==================

void checkStatus() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(CHECK_STATUS_URL);
  int code = http.GET();

  Serial.print("CHECK STATUS | HTTP CODE: ");
  Serial.println(code);

  if (code == 200) {
    String status = http.getString();
    status.trim();
    Serial.print("RAW STATUS: [");
    Serial.print(status);
    Serial.println("]");

    exchangeMode = (status == "EXCHANGE");
  }

  http.end();
}

// ---------- SENSOR MODE ----------
void handleBottleDetection() {
  float distance = readDistance();
  Serial.print("Distance: ");
  Serial.println(distance);

  if (distance < BOTTLE_DISTANCE_CM && millis() - lastSend > 3000) {
    sendBottle();
    lastSend = millis();
  }
}

float readDistance() {
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);

  long duration = pulseIn(ECHO_PIN, HIGH, 25000);
  if (duration == 0) return 999;

  return duration * 0.034 / 2;
}

// ---------- FORCE PASS MODE ----------
void forceSendBottle() {
  if (millis() - lastSend > 5000) {
    Serial.println("FORCE PASS MODE: SEND BOTTLE");
    sendBottle();
    lastSend = millis();
  }
}

// ---------- SEND TO SERVER ----------
void sendBottle() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(UPDATE_URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String data = "bottle=1&coin=0.5";
  int code = http.POST(data);

  Serial.print("SEND BOTTLE | HTTP CODE: ");
  Serial.println(code);

  if (code > 0) {
    String response = http.getString();
    Serial.print("SERVER RESPONSE: ");
    Serial.println(response);
  }

  http.end();
}
