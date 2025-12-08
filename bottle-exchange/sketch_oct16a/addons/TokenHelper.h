#ifndef TOKEN_HELPER_H
#define TOKEN_HELPER_H

#include <Firebase_ESP_Client.h>

// ฟังก์ชันช่วยสร้าง Token สำหรับ Firebase
void tokenStatusCallback(TokenInfo info){
  Serial.printf("Info Type: %s\n", info.type.c_str());
  Serial.printf("Status: %s\n", info.status.c_str());
}

#endif