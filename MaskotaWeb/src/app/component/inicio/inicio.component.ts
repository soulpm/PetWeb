import { Component, OnInit } from '@angular/core';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-inicio',
  templateUrl: './inicio.component.html',
  styleUrls: ['./inicio.component.css']
})
export class InicioComponent implements OnInit {

  pathImageIni = environment.pathImage+"/img_ini.jpg";

  constructor() { }

  ngOnInit() {
  }

}
