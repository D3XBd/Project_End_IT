#ifndef RTDB_HELPER_H
#define RTDB_HELPER_H

#include <Firebase_ESP_Client.h>

// ฟังก์ชันช่วยเขียนและอ่านข้อมูลจาก Realtime Database
bool setIntValue(FirebaseData *fbdo, String path, int value){
  if(Firebase.RTDB.setInt(fbdo, path, value)){
    Serial.println("Set value success");
    return true;
  } else {
    Serial.print("Failed, reason: ");
    Serial.println(fbdo->errorReason());
    return false;
  }
}

#endif