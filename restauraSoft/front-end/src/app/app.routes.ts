import { Routes } from '@angular/router';
import {Login} from './components/login/login';
import {Sidebar} from './components/core/sidebar/sidebar';
import {Categorias} from './components/categorias/categorias';

export const routes: Routes = [
  {
    path: "login",
    component: Login
  },
  {
    path: "",
    component: Sidebar,
    children: [
      {
        path: "categorias",
        component: Categorias
      }
    ]
  }
];
