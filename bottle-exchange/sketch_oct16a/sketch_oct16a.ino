#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

/* ================== CONFIG ================== */

// 🔹 WiFi (Hotspot มือถือ หรือ WiFi บ้าน)
const char* WIFI_SSID = "Home-sombat_2.4G";
const char* WIFI_PASS = "sombat140197";

// 🔹 Web Server (XAMPP)
const char* STATUS_URL = "http://192.168.1.239/bottle-exchange/Proj/checkStatus.php";
const char* UPDATE_URL = "http://192.168.1.239/bottle-exchange/Proj/updateBottle.php";

// 🔹 HC-SR04 Pins
#define TRIG_PIN 5
#define ECHO_PIN 18

// 🔹 Bottle detect config
const float BOTTLE_DISTANCE_CM = 8.0;    // ระยะขวด (ปรับตรงนี้)
const unsigned long HOLD_TIME = 300;     // ต้องค้างกี่ ms ถึงนับ

/* ============================================ */

LiquidCrystal_I2C lcd(0x27, 16, 2);

bool exchangeMode = false;
bool bottleDetected = false;
unsigned long detectStartTime = 0;
unsigned long lastStatusCheck = 0;

void setup() {
  Serial.begin(115200);

  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Starting...");

  connectWiFi();

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Ready");
}

void loop() {
  // เช็คสถานะจากเว็บทุก 2 วิ
  if (millis() - lastStatusCheck > 2000) {
    checkStatusFromWeb();
    lastStatusCheck = millis();
  }

  if (exchangeMode) {
    handleBottleDetection();
  } else {
    showIdle();
  }

  delay(100);
}

/* ============ FUNCTIONS ============ */

void connectWiFi() {
  WiFi.begin(WIFI_SSID, WIFI_PASS);
  lcd.setCursor(0, 1);
  lcd.print("WiFi Connecting");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nWiFi Connected");
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Connected");
}

void checkStatusFromWeb() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(STATUS_URL);
  int code = http.GET();

  if (code == 200) {
    String status = http.getString();
    status.trim();

    exchangeMode = (status == "EXCHANGE");
    Serial.println("STATUS: " + status);
  }

  http.end();
}

void showIdle() {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("IDLE");
  lcd.setCursor(0, 1);
  lcd.print("Waiting...");
}

float readDistance() {
  long sum = 0;
  int valid = 0;

  for (int i = 0; i < 5; i++) {
    digitalWrite(TRIG_PIN, LOW);
    delayMicroseconds(2);
    digitalWrite(TRIG_PIN, HIGH);
    delayMicroseconds(10);
    digitalWrite(TRIG_PIN, LOW);

    long duration = pulseIn(ECHO_PIN, HIGH, 25000);
    if (duration > 0) {
      float d = duration * 0.034 / 2;
      if (d < 100) {   // กรองค่าหลุด
        sum += d;
        valid++;
      }
    }
    delay(20);
  }

  if (valid == 0) return 999;
  return sum / valid;
}

void handleBottleDetection() {
  float distance = readDistance();

  Serial.print("Distance: ");
  Serial.println(distance);

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("EXCHANGE");
  lcd.setCursor(0, 1);
  lcd.print("D:");
  lcd.print(distance);

  // เจอขวด
  if (distance < BOTTLE_DISTANCE_CM) {

    if (!bottleDetected) {
      bottleDetected = true;
      detectStartTime = millis();
    }

    // ค้างนานพอ = นับ
    if (millis() - detectStartTime > HOLD_TIME) {
      Serial.println("BOTTLE COUNTED");
      sendBottleToServer();

      // reset และรอขวดออก
      bottleDetected = false;
      while (readDistance() < BOTTLE_DISTANCE_CM) {
        delay(50);
      }
    }

  } else {
    bottleDetected = false;
  }
}

void sendBottleToServer() {
  if (WiFi.status() != WL_CONNECTED) return;

  HTTPClient http;
  http.begin(UPDATE_URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String data = "bottle=1&coin=0.5";
  int code = http.POST(data);

  Serial.print("HTTP CODE: ");
  Serial.println(code);

  http.end();
}