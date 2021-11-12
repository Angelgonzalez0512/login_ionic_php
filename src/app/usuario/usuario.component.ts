import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoadingController } from '@ionic/angular';
import Config from '../core/models/config';
import { AuthService } from '../core/services/auth.service';
import { StorageService } from '../core/services/storage.service';
@Component({
  selector: 'app-usuario',
  templateUrl: './usuario.component.html',
  styleUrls: ['./usuario.component.scss'],
})
export class UsuarioComponent implements OnInit {
  loading: boolean = true;
  constructor(public loadingController: LoadingController, public _auth: AuthService, public _storage: StorageService, public router: Router) {
  }
  ngOnInit() {
    if (!this._auth.user.usuario) {
      console.log('No hay usuario');
      (async () => {
        if (!this._storage.__init__) {
          const init = await this._storage.init();
        }
        const response = await this._storage.getItem("user_auth");
        if (response) {
          const { jwt, id } = JSON.parse(response);
          Config.tokenUser = jwt;
          this._auth.tokenUser = jwt;
          this._auth.autenticated(id).subscribe(data => {
            if (data?.success) {
              this._auth.user = data.data;
              this.loading = false;
            } else {
              (async () => {
                const response = await this._storage.delete("user_auth");
                this.router.navigate(["/"]);
              })()
            }
          }, erro => {
            (async () => {
              const response = await this._storage.delete("user_auth");
              this.router.navigate(["/"]);
            })()
          })
        } else {
          (async () => {
            const response = await this._storage.delete("user_auth");
            this.router.navigate(["/"]);
          })()
        }
      })()
    } else {
     
      this.loading = false;
    }

  }
 async  endSession() {
    const response = await this._storage.delete("user_auth");
    this.router.navigate(["/"]);
  }
}