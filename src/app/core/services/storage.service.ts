import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage';

@Injectable({
  providedIn: 'root'
})
export class StorageService {

  private _storage: Storage | null = null;
  public __init__:boolean = false;
  constructor(private storage: Storage) {
   
  }
  async init() {
    const storage = await this.storage.create();
    this._storage = storage;
    this.__init__=true;
    return 1;
  }
  public async setValue(key: string, value: any) {
     const save= await this._storage?.set(key, value);
     return save;
  }
  public delete(key: string) {
   return this._storage?.remove(key);
  }
  public async getItem(key:string){
    return this._storage?.get(key);
  }
  
}