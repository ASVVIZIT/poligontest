import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
  constructor(uri) {
    this.uri = uri;
  }
  list(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
    });
  }
  get(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'get',
    });
  }
  store(resource) {
    return request({
      url: '/' + this.uri,
      method: 'post',
      data: resource,
    });
  }
  update(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'put',
      data: resource,
    });
  }

  avatarupload(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id + '/avatarupload',
      method: 'post',
      data: resource,
    });
  }

  restore(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/restore',
      method: 'get',
    });
  }

  destroy(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'delete',
    });
  }
}

export { Resource as default };
