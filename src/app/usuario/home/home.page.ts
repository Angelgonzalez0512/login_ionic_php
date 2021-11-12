import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AlertController } from '@ionic/angular';
import { StorageService } from 'src/app/core/services/storage.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.scss'],
})
export class HomePage implements OnInit {
  constructor(public alertController:AlertController,public _storage:StorageService,public router:Router) { }
  ngOnInit() {
  }
  async presentAlertConfirm() {
    const alert = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Cerrar cesion!',
      message: 'Desea cerrar su <strong>sesión ?</strong>',
      buttons: [
        {
          text: 'No',
          role: 'cancel',
          cssClass: 'secondary',
          handler: (blah) => {
          }
        }, {
          text: 'Si',
          handler: () => {
            if(this._storage.__init__){
              (async() => {
                const exit=await this._storage.delete("user_auth");
                this.router.navigate(['/']);
              })()
            }
          }
        }
      ]
    });

    await alert.present();
  }

}
