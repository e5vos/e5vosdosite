import { CapacitorConfig } from "@capacitor/cli";




const config: CapacitorConfig = {
  appId: "hu.e5vosdo.app",
  appName: "E5vös DÖ",
  webDir: "dist",
  bundledWebRuntime: false,
  server:{
    url: "http://192.168.56.1:5173",
    cleartext: true,
  }
  
};

export default config;
