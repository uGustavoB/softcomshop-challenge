import {Routes} from '@angular/router';
import {Login} from './components/login/login';
import {Sidebar} from './components/core/sidebar/sidebar';
import {Categorias} from './components/categorias/categorias';
import {Pratos} from './components/pratos/pratos';
import {Mesas} from './components/mesas/mesas';
import {Pedidos} from './components/pedidos/pedidos';
import {CardapioPublicoComponent} from './components/cardapio-publico/cardapio-publico';

export const routes: Routes = [
  {
    path: '',
    redirectTo: 'cardapio',
    pathMatch: 'full'
  },
  {
    path: 'login',
    component: Login
  },
  {
    path: 'cardapio',
    component: CardapioPublicoComponent
  },
  {
    path: '',
    component: Sidebar,
    children: [
      {
        path: 'pedidos',
        component: Pedidos
      },
      {
        path: 'mesas',
        component: Mesas
      },
      {
        path: 'pratos',
        component: Pratos
      },
      {
        path: 'categorias',
        component: Categorias
      }
    ]
  },
];
