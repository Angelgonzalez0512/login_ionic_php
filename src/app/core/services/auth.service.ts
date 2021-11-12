import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { User } from '../models/user';
import { BaseService } from './base.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService extends BaseService<User> {
  user: User = new User();
  constructor(public http: HttpClient) {
    super(http, "auth");
  }
  login(user: string, password: string): Observable<any> {
    return this.http.post(`${this.url}/login.php?action=login`, { correo: user, password: password });
  }
  autenticated(user: any): Observable<any> {
    return this.http.post(`${this.url}/login.php?action=autenticated`,{user},{headers:this.headers()});
  }
}
