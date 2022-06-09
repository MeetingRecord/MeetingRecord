import { SERVER_URL, SCRIPT_PATH } from "./serverDef.js";

export class DataBaseAccessProxy {

  // ユーザ認証
  static async DoAuthenticate(userName, password) {

    let params = new URLSearchParams();
    params.append("username", userName);
    params.append("password", password);
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "doAuthenticate.php", params);
    //console.log(result.data);
    return result.data.result && (result.data.detail == "NORMAL_END");
  }

  // ユーザ追加
  static async AddUser(userName, password) {

    let params = new URLSearchParams();
    params.append("username", userName);
    params.append("password", password);
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "addUser.php", params);
    //console.log(result.data);
    return result.data.result && (result.data.detail == "NORMAL_END");
  }

  // ユーザ追加
  // channelName    :追加するチャンネル名 
  static async AddChannel(channelName) {

    let params = new URLSearchParams();
    params.append("channelname", channelName);
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "addChannel.php", params);
    //console.log(result.data);
    return result.data.result && (result.data.detail == "NORMAL_END");
  }

  // チャンネルリスト取得
  static async GetChannelList() {

    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "getChannelList.php", null);
    //console.log(result.data.channelList);
    return result.data.channelList;
  }

  // チャットリスト取得
  // channelName    :チャンネル名
  static async GetChatList(channelName) {

    let params = new URLSearchParams();
    params.append("channelname", channelName);
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "getChatList.php", params);
    //console.log(result.data);
    return result.data.chatList;
  }

  // チャット投稿
  // channelName    :チャンネル名
  // chatText       :チャットテキスト
  static async PostChatText(channelName, chatText) {

    let params = new URLSearchParams();
    params.append("channelname", channelName);
    params.append("chattext", chatText);
    
    let result = await axios.post(SERVER_URL + SCRIPT_PATH + "postChatText.php", params)
    //console.log(result.data);
    return result.data.result && (result.data.detail == "NORMAL_END");
  }

}