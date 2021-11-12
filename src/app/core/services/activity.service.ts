import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { User } from '../models/user';
import { BaseService } from './base.service';

@Injectable({
  providedIn: 'root'
})
export class ActivityService extends BaseService<User> {
  constructor(public http:HttpClient) {
    super(http,"activity");
   }
}