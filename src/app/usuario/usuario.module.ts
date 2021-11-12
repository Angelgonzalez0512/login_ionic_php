import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UsuarioRoutingModule } from './usuario-routing.module';
import { UsuarioComponent } from './usuario.component';
import { IonicModule } from '@ionic/angular';
import { FormsModule } from '@angular/forms';
import { NgSelectModule } from '@ng-select/ng-select';
@NgModule({
  declarations: [
    UsuarioComponent
  ],
  imports: [
    CommonModule,
    IonicModule,
    FormsModule,
    NgSelectModule,
    UsuarioRoutingModule
  ]
})
export class UsuarioModule { }
