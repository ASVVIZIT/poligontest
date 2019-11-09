import Resource from '@/api/resource';
import request from '@/utils/request';

class ReportResource extends Resource {
  constructor() {
    super('reports');
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

export function fetchReport(id) {
  return request({
    url: '/reports/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: '/reports/' + id + '/pageviews',
    method: 'get',
  });
}

export function createReport(data) {
  return request({
    url: '/reports/create',
    method: 'post',
    data,
  });
}

export function fetchList(query) {
  return request({
    url: '/reports',
    method: 'get',
    params: query,
  });
}

export { ReportResource as default };
