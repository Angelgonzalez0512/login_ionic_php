import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Inject, Injectable } from '@angular/core';
import Config from '../models/config';

@Injectable({
  providedIn: 'root'
})
export class BaseService<T>{
  public host: string = Config.host;
  public tokenUser: string = Config.tokenUser;
  constructor(public http: HttpClient, @Inject("link") public link: string) {
  }
  get url(): string {
    return `${this.host}/${this.link}`;
  }
  headers() {
    if (!this.tokenUser) {
      this.refreshToken();
    }
    
    const autorization = new HttpHeaders({
      "Authorization": `${this.tokenUser}`,
    });
    return autorization
  }
  refreshToken() {
    this.tokenUser = Config.tokenUser;

  }
}
