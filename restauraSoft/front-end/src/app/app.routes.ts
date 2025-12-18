import {Routes} from '@angular/router';
import {Login} from './components/login/login';
import {Sidebar} from './components/core/sidebar/sidebar';
import {Categorias} from './components/categorias/categorias';
import {Pratos} from './components/pratos/pratos';

export const routes: Routes = [
  {
    path: 'login',
    component: Login
  },
  {
    path: '',
    component: Sidebar,
    children: [
      {
        path: 'pratos',
        component: Pratos
      },
      {
        path: 'categorias',
        component: Categorias
      },
      {
        path: '',
        redirectTo: 'pedidos',
        pathMatch: 'full'
      }
    ]
  },
];
