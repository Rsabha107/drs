@extends('main.layout.dashboard')
@section('main')

<div class="content">
<div class="pb-5">
          <div class="row g-4">
            <div class="col-12 col-xxl-6">
              <div class="mb-8">
                <h2 class="mb-2">Events Dashboard</h2>
                <h5 class="text-700 fw-semi-bold">Here’s what’s going on at your business right now</h5>
              </div>
              <div class="row align-items-center g-4">
                <div class="col-12 col-md-auto">
                  <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span class="fa-solid fa-square fa-stack-2x text-success-300" data-fa-transform="down-4 rotate--10 left-4"></span><span class="fa-solid fa-circle fa-stack-2x stack-circle text-success-100" data-fa-transform="up-4 right-3 grow-2"></span><span class="fa-stack-1x fa-solid fa-star text-success " data-fa-transform="shrink-2 up-8 right-6"></span></span>
                    <div class="ms-3">
                      <h4 class="mb-0">57 new orders</h4>
                      <p class="text-800 fs--1 mb-0">Awating processing</p>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-auto">
                  <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span class="fa-solid fa-square fa-stack-2x text-warning-300" data-fa-transform="down-4 rotate--10 left-4"></span><span class="fa-solid fa-circle fa-stack-2x stack-circle text-warning-100" data-fa-transform="up-4 right-3 grow-2"></span><span class="fa-stack-1x fa-solid fa-pause text-warning " data-fa-transform="shrink-2 up-8 right-6"></span></span>
                    <div class="ms-3">
                      <h4 class="mb-0">5 orders</h4>
                      <p class="text-800 fs--1 mb-0">On hold</p>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-auto">
                  <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span class="fa-solid fa-square fa-stack-2x text-danger-300" data-fa-transform="down-4 rotate--10 left-4"></span><span class="fa-solid fa-circle fa-stack-2x stack-circle text-danger-100" data-fa-transform="up-4 right-3 grow-2"></span><span class="fa-stack-1x fa-solid fa-xmark text-danger " data-fa-transform="shrink-2 up-8 right-6"></span></span>
                    <div class="ms-3">
                      <h4 class="mb-0">15 products</h4>
                      <p class="text-800 fs--1 mb-0">Out of stock</p>
                    </div>
                  </div>
                </div>
              </div>
              <hr class="bg-200 mb-6 mt-4" />
              <div class="row flex-between-center mb-4 g-3">
                <div class="col-auto">
                  <h3>Total sells</h3>
                  <p class="text-700 lh-sm mb-0">Payment received across all channels</p>
                </div>
                <!-- <div class="col-8 col-sm-4">
                  <select class="form-select form-select-sm mt-2" id="select-gross-revenue-month">
                    <option>Mar 1 - 31, 2022</option>
                    <option>April 1 - 30, 2022</option>
                    <option>May 1 - 31, 2022</option>
                  </select>
                </div> -->
              </div>
              <div class="echart-total-sales-chart" style="min-height:320px;width:100%"></div>
            </div>
            <div class="col-12 col-xxl-6">
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <div>
                          <h5 class="mb-1">Budget Distribution againts Departments<span class="badge badge-phoenix badge-phoenix-warning rounded-pill fs--1 ms-2">
                            <span class="badge-label"></span></span></h5>
                          <h6 class="text-700"></h6>
                        </div>
                        <h4></h4>
                      </div>
                      <div class="d-flex justify-content-center px-4 py-6">
                        <div class="echart-pie-edge-align-chart-example" style="height:130px;width:100%;"></div>
                      </div>
                      <div class="mt-2">
                        <div class="d-flex align-items-center mb-2">
                          <!-- <div class="bullet-item bg-primary me-2"></div> -->
                          <!-- <h6 class="text-900 fw-semi-bold flex-1 mb-0">Completed</h6>
                          <h6 class="text-900 fw-semi-bold mb-0">52%</h6> -->
                        </div>
                        <div class="d-flex align-items-center">
                          <!-- <div class="bullet-item bg-primary-100 me-2"></div> -->
                          <!-- <h6 class="text-900 fw-semi-bold flex-1 mb-0">Pending payment</h6>
                          <h6 class="text-900 fw-semi-bold mb-0">48%</h6> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <div>
                          <h5 class="mb-1">New customers<span class="badge badge-phoenix badge-phoenix-warning rounded-pill fs--1 ms-2"> <span class="badge-label">+26.5%</span></span></h5>
                          <h6 class="text-700">Last 7 days</h6>
                        </div>
                        <h4>356</h4>
                      </div>
                      <div class="pb-0 pt-4">
                        <div class="echarts-new-customers" style="height:180px;width:100%;"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <div>
                          <h5 class="mb-2">Top coupons</h5>
                          <h6 class="text-700">Last 7 days</h6>
                        </div>
                      </div>
                      <div class="pb-4 pt-3">
                        <div class="echart-top-coupons" style="height:115px;width:100%;"></div>
                      </div>
                      <div>
                        <div class="d-flex align-items-center mb-2">
                          <div class="bullet-item bg-primary me-2"></div>
                          <h6 class="text-900 fw-semi-bold flex-1 mb-0">Percentage discount</h6>
                          <h6 class="text-900 fw-semi-bold mb-0">72%</h6>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                          <div class="bullet-item bg-primary-200 me-2"></div>
                          <h6 class="text-900 fw-semi-bold flex-1 mb-0">Fixed card discount</h6>
                          <h6 class="text-900 fw-semi-bold mb-0">18%</h6>
                        </div>
                        <div class="d-flex align-items-center">
                          <div class="bullet-item bg-info-500 me-2"></div>
                          <h6 class="text-900 fw-semi-bold flex-1 mb-0">Fixed product discount</h6>
                          <h6 class="text-900 fw-semi-bold mb-0">10%</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                      <div class="d-flex justify-content-between">
                        <div>
                          <h5 class="mb-2">Paying vs non paying</h5>
                          <h6 class="text-700">Last 7 days</h6>
                        </div>
                      </div>
                      <div class="d-flex justify-content-center pt-3 flex-1">
                        <div class="echarts-paying-customer-chart" style="height:100%;width:100%;"></div>
                      </div>
                      <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                          <div class="bullet-item bg-primary me-2"></div>
                          <h6 class="text-900 fw-semi-bold flex-1 mb-0">Paying customer</h6>
                          <h6 class="text-900 fw-semi-bold mb-0">30%</h6>
                        </div>
                        <div class="d-flex align-items-center">
                          <div class="bullet-item bg-primary-100 me-2"></div>
                          <h6 class="text-900 fw-semi-bold flex-1 mb-0">Non-paying customer</h6>
                          <h6 class="text-900 fw-semi-bold mb-0">70%</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="mx-lg-n4 mt-3">
          <div class="row g-3">
            <div class="col-12 col-xl-6 col-xxl-7">
              <div class="card todo-list h-100">
                <div class="card-header border-bottom-0 pb-0">
                  <div class="row justify-content-between align-items-center mb-4">
                    <div class="col-auto">
                      <h3 class="text-1100">To do</h3>
                      <p class="mb-0 text-700">Task assigned to me</p>
                    </div>
                    <div class="col-auto w-100 w-md-auto">
                      <div class="row align-items-center g-0 justify-content-between">
                        <div class="col-12 col-sm-auto">
                          <div class="search-box w-100 mb-2 mb-sm-0" style="max-width:30rem;">
                            <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                              <input class="form-control search-input search" type="search" placeholder="Search tasks" aria-label="Search" />
                              <span class="fas fa-search search-box-icon"></span>

                            </form>
                          </div>
                        </div>
                        <div class="col-auto d-flex">
                          <p class="mb-0 ms-sm-3 fs--1 text-700 fw-bold"><span class="fas fa-filter me-1 fw-extra-bold fs--2"></span>23 tasks</p>
                          <button class="btn btn-link p-0 ms-3 fs--1 text-primary fw-bold"><span class="fas fa-sort me-1 fw-extra-bold fs--2"></span>Sorting</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body py-0 scrollbar to-do-list-body">
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-0" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Designing the dungeon</label><span class="badge badge-phoenix ms-auto fs--2 badge-phoenix-primary">DRAFT</span>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>2</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">12 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">12:00 PM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-1" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Hiring a motion graphic designer</label><span class="badge badge-phoenix ms-auto fs--2 badge-phoenix-warning">URGENT</span>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>2</a><a class="text-warning fw-bold fs--2 me-2" href="#!"><span class="fas fa-tasks me-1"></span>3</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">12 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">12:00 PM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-2" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Daily Meetings Purpose, participants</label><span class="badge badge-phoenix ms-auto fs--2 badge-phoenix-info">ON PROCESS</span>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>4</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">12 Dec, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">05:00 AM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-3" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Finalizing the geometric shapes</label>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>3</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">12 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">12:00 PM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-4" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Daily meeting with team members</label>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center">
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">1 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">12:00 PM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-5" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Daily Standup Meetings</label>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-warning fw-bold fs--2 me-2" href="#!"><span class="fas fa-tasks me-1"></span>4</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">13 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">10:00 PM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-6" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Procrastinate for a month</label><span class="badge badge-phoenix ms-auto fs--2 badge-phoenix-info">ON PROCESS</span>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>3</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">12 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">12:00 PM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-7" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">warming up</label><span class="badge badge-phoenix ms-auto fs--2 badge-phoenix-secondary">CLOSE</span>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>3</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">12 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">12:00 PM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-8" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Make ready for release</label>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>2</a><a class="text-warning fw-bold fs--2 me-2" href="#!"><span class="fas fa-tasks me-1"></span>2</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">2o Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">1:00 AM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-9" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Modify the component</label>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>4</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">22 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">1:00 AM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex hover-actions-trigger py-3 border-top border-bottom">
                    <input class="form-check-input form-check-input-todolist flex-shrink-0 my-1 me-2 form-check-input-undefined" type="checkbox" id="checkbox-todo-10" data-event-propagation-prevent="data-event-propagation-prevent" />
                    <div class="row justify-content-between align-items-md-center btn-reveal-trigger border-200 gx-0 flex-1 cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="mb-1 mb-md-0 d-flex align-items-center lh-1">
                          <label class="form-check-label mb-1 mb-md-0 mb-xl-1 mb-xxl-0 fs-0 me-2 line-clamp-1 text-900 cursor-pointer">Delete overlapping tasks and articles</label><span class="badge badge-phoenix ms-auto fs--2 badge-phoenix-secondary">CLOSE</span>
                        </div>
                      </div>
                      <div class="col-12 col-md-auto col-xl-12 col-xxl-auto">
                        <div class="d-flex lh-1 align-items-center"><a class="text-700 fw-bold fs--2 me-2" href="#!"><span class="fas fa-paperclip me-1"></span>2</a>
                          <p class="text-700 fs--2 mb-md-0 me-2 me-md-3 me-xl-2 me-xxl-3 mb-0">25 Nov, 2021</p>
                          <div class="hover-md-hide hover-xl-show hover-xxl-hide">
                            <p class="text-700 fs--2 fw-bold mb-md-0 mb-0 ps-md-3 ps-xl-0 ps-xxl-3 border-start-md border-xl-0 border-start-xxl border-300">1:00 AM</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-none d-md-block d-xl-none d-xxl-block end-0 position-absolute" style="top: 23%;" data-event-propagation-prevent="data-event-propagation-prevent">
                      <div class="hover-actions end-0" data-event-propagation-prevent="data-event-propagation-prevent">
                        <button class="btn btn-phoenix-secondary btn-icon me-1 fs--2 text-900 px-0 me-1" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-edit"></span></button>
                        <button class="btn btn-phoenix-secondary btn-icon fs--2 text-danger px-0" data-event-propagation-prevent="data-event-propagation-prevent"><span class="fas fa-trash"></span></button>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content bg-soft overflow-hidden">
                        <div class="modal-header px-6 py-5 border-300 pe-sm-5 px-md-6 dark__bg-1100">
                          <h3 class="text-1000 fw-bolder mb-0">Designing the Dungeon Blueprint</h3>
                          <button class="btn btn-phoenix-secondary btn-icon btn-icon-xl flex-shrink-0" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fa-solid fa-xmark"></span></button>
                        </div>
                        <div class="modal-body bg-100 px-6 py-0">
                          <div class="row gx-14">
                            <div class="col-12 col-lg-7 border-end-lg border-300">
                              <div class="py-6">
                                <div class="mb-7">
                                  <div class="d-flex align-items-center mb-3">
                                    <h4 class="text-900 me-3">Description</h4><a class="btn btn-link text-decoration-none p-0" href="#!"><span class="fa-solid fa-pen"></span></a>
                                  </div>
                                  <p class="text-1000 mb-0">The female circus horse-rider is a recurring subject in Chagall’s work. In 1926 the art dealer Ambroise Vollard invited Chagall to make a project based on the circus. They visited Paris’s historic Cirque d’Hiver Bouglione together; Vollard lent Chagall his private box seats. Chagall completed 19 gouaches Chagall’s work. In 1926 the art dealer Ambroise Vollard invited Chagall to make a project based on the circus.</p>
                                </div>
                                <div class="mb-7">
                                  <h4 class="mb-3">Subtasks</h4>
                                  <div class="d-flex flex-between-center hover-actions-trigger border-300 py-3 border-top">
                                    <div class="form-check mb-1 mb-md-0 d-flex align-items-center lh-1 min-h-auto">
                                      <input class="subtask-checkbox form-check-input form-check-line-through mt-0 me-3" type="checkbox" id="subtaskundefined1" />
                                      <label class="form-check-label mb-0 fs-0" for="subtaskundefined1">Study Dragons</label>
                                    </div>
                                    <div class="hover-actions end-0">
                                      <button class="btn btn-sm me-1 fs--2 text-700 px-0 me-3"><span class="fa-solid fa-pencil"></span></button>
                                      <button class="btn btn-sm text-700 px-0"><span class="fa-solid fa-xmark fs-0"></span></button>
                                    </div>
                                  </div>
                                  <div class="d-flex flex-between-center hover-actions-trigger border-300 py-3 border-top">
                                    <div class="form-check mb-1 mb-md-0 d-flex align-items-center lh-1 min-h-auto">
                                      <input class="subtask-checkbox form-check-input form-check-line-through mt-0 me-3" type="checkbox" id="subtaskundefined2" />
                                      <label class="form-check-label mb-0 fs-0" for="subtaskundefined2">Procrastinate a bit</label>
                                    </div>
                                    <div class="hover-actions end-0">
                                      <button class="btn btn-sm me-1 fs--2 text-700 px-0 me-3"><span class="fa-solid fa-pencil"></span></button>
                                      <button class="btn btn-sm text-700 px-0"><span class="fa-solid fa-xmark fs-0"></span></button>
                                    </div>
                                  </div>
                                  <div class="d-flex flex-between-center hover-actions-trigger border-300 py-3 border-top border-bottom mb-3">
                                    <div class="form-check mb-1 mb-md-0 d-flex align-items-center lh-1 min-h-auto">
                                      <input class="subtask-checkbox form-check-input form-check-line-through mt-0 me-3" type="checkbox" id="subtaskundefined3" />
                                      <label class="form-check-label mb-0 fs-0" for="subtaskundefined3">Staring at the notebook for 5 mins</label>
                                    </div>
                                    <div class="hover-actions end-0">
                                      <button class="btn btn-sm me-1 fs--2 text-700 px-0 me-3"><span class="fa-solid fa-pencil"></span></button>
                                      <button class="btn btn-sm text-700 px-0"><span class="fa-solid fa-xmark fs-0"></span></button>
                                    </div>
                                  </div><a class="fw-bold fs--1" href="#!"><span class="fas fa-plus me-1"></span>Add subtask</a>
                                </div>
                                <div class="mb-3">
                                  <div>
                                    <h4 class="mb-3">Files</h4>
                                  </div>
                                  <div class="border-top border-300 px-0 pt-4 pb-3">
                                    <div class="me-n3">
                                      <div class="d-flex flex-between-center">
                                        <div class="d-flex mb-1"><span class="fa-solid fa-image me-2 text-700 fs--1"></span>
                                          <p class="text-1000 mb-0 lh-1">Silly_sight_1.png</p>
                                        </div>
                                        <div class="font-sans-serif btn-reveal-trigger">
                                          <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h"></span></button>
                                          <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Edit</a><a class="dropdown-item text-danger" href="#!">Delete</a><a class="dropdown-item" href="#!">Download</a><a class="dropdown-item" href="#!">Report abuse</a></div>
                                        </div>
                                      </div>
                                      <div class="d-flex fs--1 text-700 mb-2 flex-wrap"><span>768 kb</span><span class="text-400 mx-1">| </span><a href="#!">Shantinan Mekalan </a><span class="text-400 mx-1">| </span><span class="text-nowrap">21st Dec, 12:56 PM</span></div><img class="rounded-2" src="../assets/img/generic/40.png" alt="" style="max-width:230px" />
                                    </div>
                                  </div>
                                  <div class="border-top border-300 px-0 pt-4 pb-3">
                                    <div class="me-n3">
                                      <div class="d-flex flex-between-center">
                                        <div>
                                          <div class="d-flex align-items-center mb-1"><span class="fa-solid fa-image me-2 fs--1 text-700"></span>
                                            <p class="text-1000 mb-0 lh-1">All_images.zip</p>
                                          </div>
                                          <div class="d-flex fs--1 text-700 mb-0 flex-wrap"><span>12.8 mb</span><span class="text-400 mx-1">| </span><a href="#!">Yves Tanguy </a><span class="text-400 mx-1">| </span><span class="text-nowrap">19th Dec, 08:56 PM</span></div>
                                        </div>
                                        <div class="font-sans-serif btn-reveal-trigger">
                                          <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h"></span></button>
                                          <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Edit</a><a class="dropdown-item text-danger" href="#!">Delete</a><a class="dropdown-item" href="#!">Download</a><a class="dropdown-item" href="#!">Report abuse</a></div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="border-top border-bottom border-300 px-0 pt-4 pb-3">
                                    <div class="me-n3">
                                      <div class="d-flex flex-between-center">
                                        <div>
                                          <div class="d-flex align-items-center mb-1 flex-wrap"><span class="fa-solid fa-file-lines me-2 fs--1 text-700"></span>
                                            <p class="text-1000 mb-0 lh-1">Project.txt</p>
                                          </div>
                                          <div class="d-flex fs--1 text-700 mb-0 flex-wrap"><span>123 kb</span><span class="text-400 mx-1">| </span><a href="#!">Shantinan Mekalan </a><span class="text-400 mx-1">| </span><span class="text-nowrap">12th Dec, 12:56 PM</span></div>
                                        </div>
                                        <div class="font-sans-serif btn-reveal-trigger">
                                          <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h"></span></button>
                                          <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Edit</a><a class="dropdown-item text-danger" href="#!">Delete</a><a class="dropdown-item" href="#!">Download</a><a class="dropdown-item" href="#!">Report abuse</a></div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div><a class="fw-bold fs--1" href="#!"><span class="fas fa-plus me-1"></span>Add file(s)</a>
                              </div>
                            </div>
                            <div class="col-12 col-lg-5">
                              <div class="py-6">
                                <h4 class="mb-4 text-black">Others Information</h4>
                                <h5 class="text-1000 mb-2">Status</h5>
                                <select class="form-select mb-4" aria-label="Default select example">
                                  <option selected="">Select</option>
                                  <option value="1">One</option>
                                  <option value="2">Two</option>
                                  <option value="3">Three</option>
                                </select>
                                <h5 class="text-1000 mb-2">Due Date</h5>
                                <div class="flatpickr-input-container mb-4">
                                  <input class="form-control datetimepicker ps-6" type="text" placeholder="Set the due date" data-options='{"disableMobile":true}' /><span class="uil uil-calendar-alt flatpickr-icon text-700"></span>
                                </div>
                                <h5 class="text-1000 mb-2">Reminder</h5>
                                <div class="flatpickr-input-container mb-4">
                                  <input class="form-control datetimepicker ps-6" type="text" placeholder="Reminder" data-options='{"enableTime":true,"noCalendar":true,"dateFormat":"H:i","disableMobile":true,"static":true}' /><span class="uil uil-bell-school flatpickr-icon text-700"></span>
                                </div>
                                <h5 class="text-1000 mb-2">Tag</h5>
                                <div class="choices-select-container mb-6">
                                  <select class="form-select" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}'>
                                    <option value="">Select organizer...</option>
                                    <option>Massachusetts Institute of Technology</option>
                                    <option>University of Chicago</option>
                                    <option>GSAS Open Labs At Harvard</option>
                                    <option>California Institute of Technology</option>
                                  </select><span class="uil uil-tag-alt choices-icon text-700" style="top: 26%;"></span>
                                </div>
                                <div class="text-end mb-9">
                                  <button class="btn btn-phoenix-danger">Delete Task</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer border-0"><a class="fw-bold fs--1 mt-4" href="#!"><span class="fas fa-plus me-1"></span>Add new task</a></div>
              </div>
            </div>
            <div class="col-12 col-xl-6 col-xxl-5">
              <div class="card h-100">
                <div class="card-body">
                  <div class="card-title mb-1">
                    <h3 class="text-1100">Activity</h3>
                  </div>
                  <p class="text-700 mb-4">Recent activity across all projects</p>
                  <div class="timeline-vertical timeline-with-details">
                    <div class="timeline-item position-relative">
                      <div class="row g-md-3">
                        <div class="col-12 col-md-auto d-flex">
                          <div class="timeline-item-date order-1 order-md-0 me-md-4">
                            <p class="fs--2 fw-semi-bold text-600 text-end">01 DEC, 2023<br class="d-none d-md-block" /> 10:30 AM</p>
                          </div>
                          <div class="timeline-item-bar position-md-relative me-3 me-md-0 border-400">
                            <div class="icon-item icon-item-sm rounded-7 shadow-none bg-primary-100"><span class="fa-solid fa-chess text-primary-600 fs--2 dark__text-primary-300"></span></div><span class="timeline-bar border-end border-dashed border-400"></span>
                          </div>
                        </div>
                        <div class="col">
                          <div class="timeline-item-content ps-6 ps-md-3">
                            <h5 class="fs--1 lh-sm">Phoenix Template: Unleashing Creative Possibilities</h5>
                            <p class="fs--1">by <a class="fw-semi-bold" href="#!">Shantinon Mekalan</a></p>
                            <p class="fs--1 text-800 mb-5">Discover limitless creativity with the Phoenix template! Our latest update offers an array of innovative features and design options.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="timeline-item position-relative">
                      <div class="row g-md-3">
                        <div class="col-12 col-md-auto d-flex">
                          <div class="timeline-item-date order-1 order-md-0 me-md-4">
                            <p class="fs--2 fw-semi-bold text-600 text-end">05 DEC, 2023<br class="d-none d-md-block" /> 12:30 AM</p>
                          </div>
                          <div class="timeline-item-bar position-md-relative me-3 me-md-0 border-400">
                            <div class="icon-item icon-item-sm rounded-7 shadow-none bg-primary-100"><span class="fa-solid fa-dove text-primary-600 fs--2 dark__text-primary-300"></span></div><span class="timeline-bar border-end border-dashed border-400"></span>
                          </div>
                        </div>
                        <div class="col">
                          <div class="timeline-item-content ps-6 ps-md-3">
                            <h5 class="fs--1 lh-sm">Empower Your Digital Presence: The Phoenix Template Unveiled</h5>
                            <p class="fs--1">by <a class="fw-semi-bold" href="#!">Bookworm22</a></p>
                            <p class="fs--1 text-800 mb-5">Unveiling the Phoenix template, a game-changer for your digital presence. With its powerful features and sleek design,</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="timeline-item position-relative">
                      <div class="row g-md-3">
                        <div class="col-12 col-md-auto d-flex">
                          <div class="timeline-item-date order-1 order-md-0 me-md-4">
                            <p class="fs--2 fw-semi-bold text-600 text-end">15 DEC, 2023<br class="d-none d-md-block" /> 2:30 AM</p>
                          </div>
                          <div class="timeline-item-bar position-md-relative me-3 me-md-0 border-400">
                            <div class="icon-item icon-item-sm rounded-7 shadow-none bg-primary-100"><span class="fa-solid fa-dungeon text-primary-600 fs--2 dark__text-primary-300"></span></div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="timeline-item-content ps-6 ps-md-3">
                            <h5 class="fs--1 lh-sm">Phoenix Template: Simplified Design, Maximum Impact</h5>
                            <p class="fs--1">by <a class="fw-semi-bold" href="#!">Sharuka Nijibum</a></p>
                            <p class="fs--1 text-800 mb-0">Introducing the Phoenix template, where simplified design meets maximum impact. Elevate your digital presence with its sleek and intuitive features.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-white pt-6 border-top border-300">
              <div id="projectSummary" data-list='{"valueNames":["project","assigness","start","deadline","calculation","projectprogress","status","action"],"page":6,"pagination":true}'>
                <div class="row align-items-end justify-content-between pb-4 g-3">
                  <div class="col-auto">
                    <h3>Events</h3>
                    <p class="text-700 lh-sm mb-0">Brief summary of all Events</p>
                  </div>
                </div>
                <div class="table-responsive ms-n1 ps-1 scrollbar">
                  <table class="table fs--1 mb-0 border-top border-200">
                    <thead>
                      <tr>
                        <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="project" style="width:30%;">PROJECT NAME</th>
                        <th class="sort align-middle ps-3" scope="col" data-sort="assigness" style="width:10%;">ASSIGNESS</th>
                        <th class="sort align-middle ps-3" scope="col" data-sort="start" style="width:10%;">START DATE</th>
                        <th class="sort align-middle ps-3" scope="col" data-sort="deadline" style="width:15%;">DEADLINE</th>
                        <th class="sort align-middle ps-3" scope="col" data-sort="calculation" style="width:12%;">CALCULATION</th>
                        <th class="sort align-middle ps-3" scope="col" data-sort="projectprogress" style="width:5%;">PROGRESS</th>
                        <th class="sort align-middle ps-8" scope="col" data-sort="status" style="width:10%;">STATUS</th>
                        <th class="sort align-middle text-end" scope="col" style="width:10%;"></th>
                      </tr>
                    </thead>
                    <tbody class="list" id="project-summary-table-body">
                      <tr class="position-static">
                        <td class="align-middle time white-space-nowrap ps-0 project"><a class="fw-bold fs-0" href="#">Making the Butterflies shoot each other dead</a></td>
                        <td class="align-middle white-space-nowrap assigness ps-3">
                          <div class="avatar-group avatar-group-dense"><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/9.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/9.webp" alt="" /></div>
                                    <h6 class="text-white light">Michael Jenkins</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/25.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/25.webp" alt="" /></div>
                                    <h6 class="text-white light">Ansolo Lazinatov</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/32.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/32.webp" alt="" /></div>
                                    <h6 class="text-white light">Jennifer Schramm</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle avatar-placeholder" src="../assets/img/team/avatar.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/avatar.webp" alt="" /></div>
                                    <h6 class="text-white light">Kristine Cadena</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div>
                            <div class="avatar avatar-s  rounded-circle">
                              <div class="avatar-name rounded-circle "><span>+3</span></div>
                            </div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap start ps-3">
                          <p class="mb-0 fs--1 text-900">Dec 12, 2018</p>
                        </td>
                        <td class="align-middle white-space-nowrap deadline ps-3">
                          <p class="mb-0 fs--1 text-900">Dec 12, 2026</p>
                        </td>
                        <td class="align-middle white-space-nowrap calculation ps-3">
                          <p class="fw-bold text-1100 fs--1 mb-0">$4</p>
                          <p class="fw-semi-bold fs--2 text-700 mb-0">Cost</p>
                        </td>
                        <td class="align-middle white-space-nowrap ps-3 projectprogress">
                          <p class="text-800 fs--2 mb-0">145 / 145</p>
                          <div class="progress" style="height:3px;">
                            <div class="progress-bar bg-success" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap ps-8 status">
                          <div class="progress progress-stack mt-3" style="height:3px;">
                            <div class="progress-bar bg-info" style="width:30%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                            <div class="progress-bar bg-danger" style="width:5%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="5% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-warning" style="width:45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="45% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-success" style="width:15%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="15% Damage" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle text-end white-space-nowrap pe-0 action">
                          <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr class="position-static">
                        <td class="align-middle time white-space-nowrap ps-0 project"><a class="fw-bold fs-0" href="#">Project Doughnut Dungeon</a></td>
                        <td class="align-middle white-space-nowrap assigness ps-3">
                          <div class="avatar-group avatar-group-dense"><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/22.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/22.webp" alt="" /></div>
                                    <h6 class="text-white light">Woodrow Burton</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/28.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/28.webp" alt="" /></div>
                                    <h6 class="text-white light">Ashley Garrett</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s">
                                <div class="avatar-name rounded-circle"><span>R</span></div>
                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2">
                                      <div class="avatar-name rounded-circle"><span>R</span></div>
                                    </div>
                                    <h6 class="text-white light">Raymond Mims</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap start ps-3">
                          <p class="mb-0 fs--1 text-900">Jan 9, 2019</p>
                        </td>
                        <td class="align-middle white-space-nowrap deadline ps-3">
                          <p class="mb-0 fs--1 text-900">Dec 9, 2022</p>
                        </td>
                        <td class="align-middle white-space-nowrap calculation ps-3">
                          <button class="btn btn-phoenix-secondary square-icon-btn"><span class="fas fa-plus"></span></button>
                        </td>
                        <td class="align-middle white-space-nowrap ps-3 projectprogress">
                          <p class="text-800 fs--2 mb-0">148 / 223</p>
                          <div class="progress" style="height:3px;">
                            <div class="progress-bar bg-success" style="width: 66.3677130044843%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap ps-8 status">
                          <div class="progress progress-stack mt-3" style="height:3px;">
                            <div class="progress-bar bg-info" style="width:20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                            <div class="progress-bar bg-danger" style="width:15%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="5% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-warning" style="width:45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="45% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-success" style="width:30%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="15% Damage" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle text-end white-space-nowrap pe-0 action">
                          <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr class="position-static">
                        <td class="align-middle time white-space-nowrap ps-0 project"><a class="fw-bold fs-0" href="#">The Chewing Gum Attack</a></td>
                        <td class="align-middle white-space-nowrap assigness ps-3">
                          <div class="avatar-group avatar-group-dense"><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/34.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/34.webp" alt="" /></div>
                                    <h6 class="text-white light">Jean Renoir</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/59.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/59.webp" alt="" /></div>
                                    <h6 class="text-white light">Katerina Karenin</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap start ps-3">
                          <p class="mb-0 fs--1 text-900">Sep 4, 2019</p>
                        </td>
                        <td class="align-middle white-space-nowrap deadline ps-3">
                          <p class="mb-0 fs--1 text-900">Dec 4, 2021</p>
                        </td>
                        <td class="align-middle white-space-nowrap calculation ps-3">
                          <p class="fw-bold text-1100 fs--1 mb-0">$657k</p>
                          <p class="fw-semi-bold fs--2 text-700 mb-0">Estimation</p>
                        </td>
                        <td class="align-middle white-space-nowrap ps-3 projectprogress">
                          <p class="text-800 fs--2 mb-0">277 / 539</p>
                          <div class="progress" style="height:3px;">
                            <div class="progress-bar bg-success" style="width: 51.39146567717996%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap ps-8 status">
                          <div class="progress progress-stack mt-3" style="height:3px;">
                            <div class="progress-bar bg-info" style="width:10%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                            <div class="progress-bar bg-danger" style="width:10%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="5% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-warning" style="width:35%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="45% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-success" style="width:45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="15% Damage" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle text-end white-space-nowrap pe-0 action">
                          <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr class="position-static">
                        <td class="align-middle time white-space-nowrap ps-0 project"><a class="fw-bold fs-0" href="#">Execution of Micky the foul mouse</a></td>
                        <td class="align-middle white-space-nowrap assigness ps-3">
                          <div class="avatar-group avatar-group-dense"><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/1.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/1.webp" alt="" /></div>
                                    <h6 class="text-white light">Luis Bunuel</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle avatar-placeholder" src="../assets/img/team/avatar.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/avatar.webp" alt="" /></div>
                                    <h6 class="text-white light">Kristine Cadena</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/5.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/5.webp" alt="" /></div>
                                    <h6 class="text-white light">Ricky Antony</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/11.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/11.webp" alt="" /></div>
                                    <h6 class="text-white light">Roy Anderson</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap start ps-3">
                          <p class="mb-0 fs--1 text-900">Nov 1, 2019</p>
                        </td>
                        <td class="align-middle white-space-nowrap deadline ps-3">
                          <p class="mb-0 fs--1 text-900">Dec 1, 2024</p>
                        </td>
                        <td class="align-middle white-space-nowrap calculation ps-3">
                          <button class="btn btn-phoenix-secondary square-icon-btn"><span class="fas fa-plus"></span></button>
                        </td>
                        <td class="align-middle white-space-nowrap ps-3 projectprogress">
                          <p class="text-800 fs--2 mb-0">16 / 56</p>
                          <div class="progress" style="height:3px;">
                            <div class="progress-bar bg-success" style="width: 28.57142857142857%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap ps-8 status">
                          <div class="progress progress-stack mt-3" style="height:3px;">
                            <div class="progress-bar bg-info" style="width:45%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                            <div class="progress-bar bg-danger" style="width:15%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="5% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-warning" style="width:20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="45% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-success" style="width:20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="15% Damage" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle text-end white-space-nowrap pe-0 action">
                          <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr class="position-static">
                        <td class="align-middle time white-space-nowrap ps-0 project"><a class="fw-bold fs-0" href="#">Harnessing stupidity from Jerry</a></td>
                        <td class="align-middle white-space-nowrap assigness ps-3">
                          <div class="avatar-group avatar-group-dense"><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/21.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/21.webp" alt="" /></div>
                                    <h6 class="text-white light">Michael Jenkins</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/23.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/23.webp" alt="" /></div>
                                    <h6 class="text-white light">Kristine Cadena</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/25.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/25.webp" alt="" /></div>
                                    <h6 class="text-white light">Ricky Antony</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap start ps-3">
                          <p class="mb-0 fs--1 text-900">Dec 28, 2019</p>
                        </td>
                        <td class="align-middle white-space-nowrap deadline ps-3">
                          <p class="mb-0 fs--1 text-900">Nov 28, 2021</p>
                        </td>
                        <td class="align-middle white-space-nowrap calculation ps-3">
                          <button class="btn btn-phoenix-secondary square-icon-btn"><span class="fas fa-plus"></span></button>
                        </td>
                        <td class="align-middle white-space-nowrap ps-3 projectprogress">
                          <p class="text-800 fs--2 mb-0">169 / 394</p>
                          <div class="progress" style="height:3px;">
                            <div class="progress-bar bg-success" style="width: 42.89340101522843%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap ps-8 status">
                          <div class="progress progress-stack mt-3" style="height:3px;">
                            <div class="progress-bar bg-info" style="width:25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                            <div class="progress-bar bg-danger" style="width:35%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="5% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-warning" style="width:20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="45% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-success" style="width:15%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="15% Damage" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle text-end white-space-nowrap pe-0 action">
                          <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr class="position-static">
                        <td class="align-middle time white-space-nowrap ps-0 project"><a class="fw-bold fs-0" href="#">Water resistant mosquito killer gun</a></td>
                        <td class="align-middle white-space-nowrap assigness ps-3">
                          <div class="avatar-group avatar-group-dense"><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/30.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/30.webp" alt="" /></div>
                                    <h6 class="text-white light">Stanly Drinkwater</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle avatar-placeholder" src="../assets/img/team/avatar.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/avatar.webp" alt="" /></div>
                                    <h6 class="text-white light">Kristine Cadena</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/59.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/59.webp" alt="" /></div>
                                    <h6 class="text-white light">Katerina Karenin</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s  rounded-circle">
                                <img class="rounded-circle " src="../assets/img/team/31.webp" alt="" />

                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2"><img class="rounded-circle border border-white" src="../assets/img/team/31.webp" alt="" /></div>
                                    <h6 class="text-white light">Martina scorcese</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div><a class="dropdown-toggle dropdown-caret-none d-inline-block" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                              <div class="avatar avatar-s">
                                <div class="avatar-name rounded-circle"><span>R</span></div>
                              </div>
                            </a>
                            <div class="dropdown-menu avatar-dropdown-menu p-0 overflow-hidden" style="width: 320px;">
                              <div class="position-relative">
                                <div class="bg-holder z-index--1" style="background-image:url(../assets/img/bg/bg-32.png);background-size: auto;">
                                </div>
                                <!--/.bg-holder-->

                                <div class="p-3">
                                  <div class="text-end">
                                    <button class="btn p-0 me-2"><span class="fa-solid fa-user-plus text-white light"></span></button>
                                    <button class="btn p-0"><span class="fa-solid fa-ellipsis text-white light"></span></button>
                                  </div>
                                  <div class="text-center">
                                    <div class="avatar avatar-xl status-online position-relative me-2 me-sm-0 me-xl-2 mb-2">
                                      <div class="avatar-name rounded-circle"><span>R</span></div>
                                    </div>
                                    <h6 class="text-white light">Roy Anderson</h6>
                                    <p class="text-600 fw-semi-bold fs--2 mb-2">@tyrion222</p>
                                    <div class="d-flex flex-center mb-3">
                                      <h6 class="text-white light mb-0">224 <span class="fw-normal text-300">connections</span></h6><span class="fa-solid fa-circle text-700 mx-1" data-fa-transform="shrink-10 up-2"></span>
                                      <h6 class="text-white light mb-0">23 <span class="fw-normal text-300">mutual</span></h6>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="bg-white">
                                <div class="p-3 border-bottom">
                                  <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-phone"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg me-2"><span class="fa-solid fa-message"></span></button>
                                      <button class="btn btn-phoenix-secondary btn-icon btn-icon-lg"><span class="fa-solid fa-video"></span></button>
                                    </div>
                                    <button class="btn btn-phoenix-primary"><span class="fa-solid fa-envelope me-2"></span>Send Email</button>
                                  </div>
                                </div>
                                <ul class="nav d-flex flex-column py-3 border-bottom">
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900 d-inline-block" data-feather="clipboard"></span><span class="text-1000 flex-1">Assigned Projects</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                  <li class="nav-item"><a class="nav-link px-3 d-flex flex-between-center" href="#!"> <span class="me-2 text-900" data-feather="pie-chart"></span><span class="text-1000 flex-1">View activiy</span><span class="fa-solid fa-chevron-right fs--3"></span></a></li>
                                </ul>
                              </div>
                              <div class="p-3 d-flex justify-content-between"><a class="btn btn-link p-0 text-decoration-none" href="#!">Details </a><a class="btn btn-link p-0 text-decoration-none text-danger" href="#!">Unassign </a></div>
                            </div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap start ps-3">
                          <p class="mb-0 fs--1 text-900">Feb 24, 2020</p>
                        </td>
                        <td class="align-middle white-space-nowrap deadline ps-3">
                          <p class="mb-0 fs--1 text-900">Nov 24, 2021</p>
                        </td>
                        <td class="align-middle white-space-nowrap calculation ps-3">
                          <p class="fw-bold text-1100 fs--1 mb-0">$55k</p>
                          <p class="fw-semi-bold fs--2 text-700 mb-0">Budget</p>
                        </td>
                        <td class="align-middle white-space-nowrap ps-3 projectprogress">
                          <p class="text-800 fs--2 mb-0">600 / 600</p>
                          <div class="progress" style="height:3px;">
                            <div class="progress-bar bg-success" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle white-space-nowrap ps-8 status">
                          <div class="progress progress-stack mt-3" style="height:3px;">
                            <div class="progress-bar bg-info" style="width:24%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" role="progressbar"></div>
                            <div class="progress-bar bg-danger" style="width:5%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="5% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-warning" style="width:35%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="45% Damage" role="progressbar"></div>
                            <div class="progress-bar bg-success" style="width:35%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip" data-bs-placement="top" title="15% Damage" role="progressbar"></div>
                          </div>
                        </td>
                        <td class="align-middle text-end white-space-nowrap pe-0 action">
                          <div class="font-sans-serif btn-reveal-trigger position-static">
                            <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                              <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="row align-items-center justify-content-between py-2 pe-0 fs--1">
                  <div class="col-auto d-flex">
                    <p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900" data-list-info="data-list-info"></p><a class="fw-semi-bold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semi-bold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                  </div>
                  <div class="col-auto d-flex">
                    <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                    <ul class="mb-0 pagination"></ul>
                    <button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <!-- </div> -->
        <!-- <footer class="footer position-absolute">
          <div class="row g-0 justify-content-between align-items-center h-100">
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 mt-2 mt-sm-0 text-900">Thank you for creating with Phoenix<span class="d-none d-sm-inline-block"></span><span class="d-none d-sm-inline-block mx-1">|</span><br class="d-sm-none" />2023 &copy;<a class="mx-1" href="https://themewagon.com">Themewagon</a></p>
            </div>
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600">v1.13.0</p>
            </div>
          </div>
        </footer> -->
      </div>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

@endsection

@push('script')

<script>
        (function(factory) {
            typeof define === "function" && define.amd ? define(factory) : factory();
        })(function() {
            "use strict";

            // import * as echarts from 'echarts';
            const {
                merge
            } = window._;

            // form config.js
            const echartSetOption = (
                chart,
                userOptions,
                getDefaultOptions,
                responsiveOptions
            ) => {
                const {
                    breakpoints,
                    resize
                } = window.phoenix.utils;
                const handleResize = (options) => {
                    Object.keys(options).forEach((item) => {
                        if (window.innerWidth > breakpoints[item]) {
                            chart.setOption(options[item]);
                        }
                    });
                };

                const themeController = document.body;
                // Merge user options with lodash
                chart.setOption(merge(getDefaultOptions(), userOptions));

                const navbarVerticalToggle = document.querySelector(
                    ".navbar-vertical-toggle"
                );
                if (navbarVerticalToggle) {
                    navbarVerticalToggle.addEventListener(
                        "navbar.vertical.toggle",
                        () => {
                            chart.resize();
                            if (responsiveOptions) {
                                handleResize(responsiveOptions);
                            }
                        }
                    );
                }

                resize(() => {
                    chart.resize();
                    if (responsiveOptions) {
                        handleResize(responsiveOptions);
                    }
                });
                if (responsiveOptions) {
                    handleResize(responsiveOptions);
                }

                themeController.addEventListener(
                    "clickControl",
                    ({
                        detail: {
                            control
                        }
                    }) => {
                        if (control === "phoenixTheme") {
                            chart.setOption(
                                window._.merge(getDefaultOptions(), userOptions)
                            );
                        }
                    }
                );
            };
            // -------------------end config.js--------------------

            const pieEdgeAlignChartInit = () => {
                const {
                    getColor,
                    getData,
                    // rgbaColor
                } = window.phoenix.utils;
                const $chartEl = document.querySelector(
                    ".echart-pie-edge-align-chart-example"
                );

                // const data = [{
                //         value: 850,
                //         name: "Starter",
                //         itemStyle: {
                //             color: rgbaColor(getColor("primary"), 0.5),
                //         },
                //     },
                //     {
                //         value: 750,
                //         name: "Starter Pro",
                //         itemStyle: {
                //             color: getColor("danger"),
                //         },
                //     },
                //     {
                //         value: 457,
                //         name: "Basic",
                //         itemStyle: {
                //             color: getColor("primary"),
                //         },
                //     },
                //     {
                //         value: 654,
                //         name: "Optimal",
                //         itemStyle: {
                //             color: getColor("secondary"),
                //         },
                //     },
                //     {
                //         value: 447,
                //         name: "Business",
                //         itemStyle: {
                //             color: getColor("warning"),
                //         },
                //     },
                //     {
                //         value: 682,
                //         name: "Classic addition",
                //         itemStyle: {
                //             color: rgbaColor(getColor("warning"), 0.8),
                //         },
                //     },
                //     {
                //         value: 471,
                //         name: "Premium",
                //         itemStyle: {
                //             color: getColor("success"),
                //         },
                //     },
                //     {
                //         value: 524,
                //         name: "Platinum",
                //         itemStyle: {
                //             color: getColor("info"),
                //         },
                //     },
                //     {
                //         value: 200,
                //         name: "Platinum Pro",
                //         itemStyle: {
                //             color: rgbaColor(getColor("primary"), 0.5),
                //         },
                //     },
                // ];

                if ($chartEl) {
                    const userOptions = getData($chartEl, "echarts");
                    const chart = window.echarts.init($chartEl);
                    const getDefaultOptions = () => ({
                        legend: {
                            // top: '-15%',
                            left: 'center',
                            textStyle: {
                                color: getColor('gray-600')
                            }
                        },
                        color: [
                            getColor('info-300'),
                            getColor('warning-300'),
                            getColor('danger-300'),
                            getColor('success-300'),
                            getColor('primary')
                        ],
                        // title: [{
                        //         text: "Pie Edge Align Chart",
                        //         left: "center",
                        //         textStyle: {
                        //             color: getColor("gray-600"),
                        //         },
                        //     },
                        //     {
                        //         subtext: 'alignTo: "edge"',
                        //         left: "50%",
                        //         top: "85%",
                        //         textAlign: "center",
                        //         subtextStyle: {
                        //             color: getColor("gray-700"),
                        //         },
                        //     },
                        // ],

                        tooltip: {
                            trigger: "item",
                            padding: [7, 10],
                            backgroundColor: getColor("gray-100"),
                            borderColor: getColor("gray-300"),
                            textStyle: {
                                color: getColor("dark")
                            },
                            borderWidth: 1,
                            transitionDuration: 0,
                            axisPointer: {
                                type: "none",
                            },
                            position(pos, ...size) {
                                if (window.innerWidth <= 540) {
                                    const tooltipHeight = size[1].offsetHeight;
                                    const obj = {
                                        top: pos[1] - tooltipHeight - 20
                                    };
                                    obj[
                                        pos[0] < size[3].viewSize[0] / 2 ?
                                        "left" :
                                        "right"
                                    ] = 5;
                                    return obj;
                                }
                                return null;
                            },
                        },

                        series: [{
                            type: "pie",
                            radius: window.innerWidth < 530 ? "45%" : "60%",
                            center: ["50%", "50%"],
                            data: <?php echo json_encode($Data); ?>,
                            label: {
                                position: "outer",
                                // alignTo: "edge",
                                formatter: '{x|{d}%}',
                                // formatter: '{x|{d}%}  {y|{b}}',
                                rich: {
                                    x: {
                                        fontSize: 11.25,
                                        fontWeight: 800,
                                        color: getColor('gray-700'),
                                        padding: [0, 0, 5, 15]
                                    },
                                    y: {
                                        fontSize: 12.8,
                                        color: getColor('gray-700'),
                                        fontWeight: 600
                                    }
                                },
                                margin: 20,
                                color: getColor("gray-700"),
                            },
                            //                 label: {
                            //   show: true,
                            //   position: 'left',
                            //   formatter: '{x|{d}%} \n {y|{b}}',
                            //   rich: {
                            //     x: {
                            //       fontSize: 11.25,
                            //       fontWeight: 800,
                            //       color: getColor('gray-700'),
                            //       padding: [0, 0, 5, 15]
                            //     },
                            //     y: {
                            //       fontSize: 12.8,
                            //       color: getColor('gray-700'),
                            //       fontWeight: 600
                            //     }
                            //   }
                            // },
                            left: "5%",
                            right: "5%",
                            top: 0,
                            bottom: 0,
                        }, ],

                    });

                    const responsiveOptions = {
                        xs: {
                            series: [{
                                radius: "45%",
                            }, ],
                        },
                        sm: {
                            series: [{
                                radius: "60%",
                            }, ],
                        },
                    };

                    echartSetOption(
                        chart,
                        userOptions,
                        getDefaultOptions,
                        responsiveOptions
                    );
                }
            };

            const {
                docReady
            } = window.phoenix.utils;

            docReady(pieEdgeAlignChartInit);
        });
    </script>

@endpush
