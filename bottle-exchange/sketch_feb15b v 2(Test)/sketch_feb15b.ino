#include <WiFi.h>
#include <HTTPClient.h>
#include <LiquidCrystal_I2C.h>

// ================== WIFI ==================
const char* ssid = "Home-sombat_2.4G";
const char* password = "sombat140197";

// ================== SERVER ==================
const char* CHECK_STATUS_URL = "http://192.168.1.239/bottle-exchange/Proj/checkStatus.php";
const char* UPDATE_URL = "http://192.168.1.239/bottle-exchange/Proj/updateBottle.php";

// ================== LCD ==================
LiquidCrystal_I2C lcd(0x27, 16, 2);

// ================== SENSOR ==================
#define TRIG_PIN 5
#define ECHO_PIN 18
#define BOTTLE_DISTANCE_CM 15.0

#define FORCE_PASS_MODE false   // <<< true = เอาผ่าน

bool exchangeMode = false;
unsigned long lastSend = 0;

// ================== SETUP ==================
void setup() {
  Serial.begin(115200);

  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  lcd.init();
  lcd.backlight();

  lcd.setCursor(0,0);
  lcd.print("Connecting WiFi");

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("WiFi Connected");
  delay(1000);
  lcd.clear();
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

    exchangeMode = (status == "EXCHANGE");

    lcd.clear();
    lcd.setCursor(0,0);

    if(exchangeMode){
      lcd.print("Mode: EXCHANGE");
      lcd.setCursor(0,1);
      lcd.print("Insert Bottle");
    }else{
      lcd.print("Mode: IDLE");
      lcd.setCursor(0,1);
      lcd.print("Not Exchanging");
    }
  }

  http.end();
}

// ---------- SENSOR MODE ----------
void handleBottleDetection() {
  float distance = readDistance();

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
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("FORCE SEND...");
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

  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Sending...");

  String data = "bottle=1&coin=0.5";
  int code = http.POST(data);

  Serial.print("SEND BOTTLE | HTTP CODE: ");
  Serial.println(code);

  if (code > 0) {
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("Bottle Added!");
    lcd.setCursor(0,1);
    lcd.print("+0.5 Coin");
    delay(1500);
  }

  http.end();
}