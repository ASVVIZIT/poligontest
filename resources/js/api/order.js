import Resource from '@/api/resource';
import request from '@/utils/request';

class OrderResource extends Resource {
  constructor() {
    super('orders');
  }

  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }

  updatePermission(id, permissions) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'put',
      data: permissions,
    });
  }
}

export function fetchOrder(id) {
  return request({
    url: '/orders/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: '/orders/' + id + '/pageviews',
    method: 'get',
  });
}

export function createOrder(data) {
  return request({
    url: '/orders/create',
    method: 'post',
    data,
  });
}

export function fetchList(query) {
  return request({
    url: '/orders',
    method: 'get',
    params: query,
  });
}

export { OrderResource as default };
